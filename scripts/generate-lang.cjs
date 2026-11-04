// Regenerates the Angular block of lang/{en,et,ru}/tedi.php from upstream's
// translations.ts. The React block at the foot of each file is kept as is.
//
//   node scripts/generate-lang.cjs [path/to/translations.ts]
//
// Defaults to .reference/angular/src/tedi/services/translation/translations.ts.
//
// Plain strings port verbatim. Function entries become Laravel strings:
//   - interpolated params        -> :param placeholders
//   - a boolean selector param   -> key.true / key.false variants
//   - an enum selector (sort)    -> key.<value> variants
//   - a count-dependent ternary  -> "{1} singular|[0,*] plural" (trans_choice)
const fs = require('fs');
const path = require('path');
const esbuild = require('esbuild');

const root = path.resolve(__dirname, '..');
const source = path.resolve(process.argv[2] || path.join(root, '.reference/angular/src/tedi/services/translation/translations.ts'));
const langDir = path.join(root, 'lang');

// translations.ts only imports a type, so a TS-strip transpile is enough.
const { code } = esbuild.transformSync(fs.readFileSync(source, 'utf8'), { loader: 'ts', format: 'cjs' });
const mod = { exports: {} };
new Function('module', 'exports', 'require', code)(mod, mod.exports, require);
const map = mod.exports.translationsMap;

const ENUMS = { 'table.sort': { param: 'direction', values: ['asc', 'desc', 'none'] } };

function params(fn) {
  const m = fn.toString().match(/^\s*(?:function\s*\w*)?\(?([^)=]*)\)?\s*=>/);
  return m[1].split(',').map((s) => s.trim().replace(/[?:].*$/, '')).filter(Boolean);
}

function render(fn, ps, overrides) {
  return fn(...ps.map((p) => (p in overrides ? overrides[p] : `:${p}`)));
}

function variants(key, fn) {
  const ps = params(fn);
  if (ENUMS[key]) {
    const { param, values } = ENUMS[key];
    return Object.fromEntries(values.map((v) => [`${key}.${v}`, render(fn, ps, { [param]: v })]));
  }
  // A selector param is one whose truthiness changes the output's shape.
  const selectors = ps.filter((p) => {
    const t = render(fn, ps, { [p]: true }).replaceAll('true', `:${p}`);
    const f = render(fn, ps, { [p]: false }).replaceAll('false', `:${p}`);
    return t !== f && !/^(count|n|page|total|slideNumber|totalNumber)$/.test(p);
  });
  if (selectors.length > 1) throw new Error(`${key}: multiple selectors ${selectors}`);
  const out = {};
  const forms = selectors.length
    ? [[`${key}.true`, { [selectors[0]]: true }], [`${key}.false`, { [selectors[0]]: false }]]
    : [[key, {}]];
  for (const [k, ov] of forms) {
    // Count-dependent wording: render with 1 and 2 and see whether only the number differs.
    const countParam = ps.find((p) => p === 'count');
    if (countParam && !(countParam in ov)) {
      const one = render(fn, ps, { ...ov, count: 1 }).replace(/\b1\b/, ':count');
      const two = render(fn, ps, { ...ov, count: 2 }).replace(/\b2\b/, ':count');
      out[k] = one === two ? two : `{1} ${one}|[0,*] ${two}`;
    } else {
      out[k] = render(fn, ps, ov);
    }
  }
  return out;
}

const php = (s) => '"' + String(s).replace(/\\/g, '\\\\').replace(/"/g, '\\"').replace(/\$/g, '\\$') + '"';

for (const lang of ['en', 'et', 'ru']) {
  const lines = [];
  for (const [key, entry] of Object.entries(map)) {
    const v = entry[lang];
    if (typeof v === 'function') {
      for (const [k, s] of Object.entries(variants(key, v))) lines.push(`    ${php(k)} => ${php(s)},`);
    } else {
      lines.push(`    ${php(key)} => ${php(v)},`);
    }
  }
  const file = path.join(langDir, lang, 'tedi.php');
  const cur = fs.readFileSync(file, 'utf8');
  const reactStart = cur.indexOf('    // From @tedi-design-system/react');
  if (reactStart < 0) throw new Error(`${file}: React block marker not found`);
  const head =
    '<?php\n\n// Generated from @tedi-design-system/angular\n' +
    '// src/tedi/services/translation/translations.ts by scripts/generate-lang.cjs — do not edit by hand.\n' +
    '// Function entries become :placeholders, key.true/key.false variants for a\n' +
    '// boolean selector, and "{1} …|[0,*] …" choice strings for count wording.\n\nreturn [\n';
  fs.writeFileSync(file, head + lines.join('\n') + '\n\n' + cur.slice(reactStart));
}
