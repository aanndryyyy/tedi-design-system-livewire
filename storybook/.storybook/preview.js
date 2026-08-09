import '../vendor/area17/blast/public/main.css';
import { themes } from '@storybook/theming';
import { addons } from '@storybook/preview-api';
import { STORY_RENDERED } from '@storybook/core-events';
import theme from './theme';

// storybook-addon-pseudo-states rewrites every :hover/:active/:focus-visible
// rule in document.styleSheets into a .pseudo-* class variant, and it does that
// once, on STORY_RENDERED. That is too early here: Blast hands Storybook a full
// HTML document whose <head> links /vendor/tedi/tedi.css, so at the moment the
// story "renders" the design system's stylesheet is still downloading and is
// not yet in document.styleSheets. The addon then marks every rule it did see
// as __processed and never revisits a sheet, so a cold load rewrites a handful
// of Storybook's own rules and none of TEDI's — the pseudo classes land on the
// buttons but nothing styles them.
//
// Re-emitting STORY_RENDERED once each late stylesheet has loaded gives the
// addon the second pass it needs. Rules it already handled are cached, so the
// repeat is cheap.
//
// The second problem is a hard cap inside the addon: rewriteStyleSheet
// (preview.mjs:113) breaks out of its loop once the rule *index* passes 1000,
// warning "Reached maximum of 1000 pseudo selectors per sheet". tedi.css is one
// sheet of ~2200 rules, so only its first half is ever rewritten — buttons and
// cards get their .pseudo-* variants, and everything further down the bundle
// (`.tedi-input:hover`, the tabs trigger, links) silently does not. The
// pseudo classes still land on the elements, so the story renders as if the
// state were forced while looking identical to Default — a worse failure than
// not shipping the row at all.
//
// Nothing in the addon is configurable here, so re-split the sheet instead:
// replace the single <link> with a run of <style> chunks, each under the cap,
// inserted at the link's position so the cascade is unchanged. Every chunk is
// then a sheet the addon processes end to end. Round-tripping through
// `cssText` is lossless for anything the browser parsed in the first place,
// and tedi.css has no @import/@charset to reposition.
//
// One thing does not survive the move: a relative url() resolves against the
// stylesheet's own URL in a <link> and against the *document* URL in a
// <style>, and Chrome keeps those relative when it serialises cssText. So
// tedi.css's `url("./fonts/material-symbols-outlined.woff2")` would start
// resolving to /fonts/… and every icon would render as its ligature name.
// Absolutise them against the link's href on the way through.
const MAX_RULES_PER_SHEET = 800;

// `#` is excluded alongside the absolute forms: `url(#gradient)` is a
// same-document SVG reference, and resolving it against the sheet would point
// it at tedi.css. `/` covers protocol-relative `//host/…` too.
const RELATIVE_URL = /url\((['"]?)(?!data:|https?:|\/|#)([^'")]+)\1\)/g;

const absolutiseUrls = (cssText, base) =>
  cssText.replace(
    RELATIVE_URL,
    (match, quote, url) => `url(${quote}${new URL(url, base).href}${quote})`
  );

const splitLargeStyleSheet = (link) => {
  let rules;

  try {
    if (!link.sheet || link.sheet.cssRules.length <= MAX_RULES_PER_SHEET) return;
    rules = Array.from(link.sheet.cssRules, (rule) =>
      absolutiseUrls(rule.cssText, link.href)
    );
  } catch (e) {
    // Cross-origin sheet — the addon can't read it either. Leave it alone.
    return;
  }

  const chunks = document.createDocumentFragment();

  for (let i = 0; i < rules.length; i += MAX_RULES_PER_SHEET) {
    const style = document.createElement('style');
    style.textContent = rules.slice(i, i + MAX_RULES_PER_SHEET).join('\n');
    chunks.appendChild(style);
  }

  link.parentNode.insertBefore(chunks, link);
  link.remove();
};

if (typeof document !== 'undefined') {
  const channel = addons.getChannel();

  const rewriteAgain = (link) => {
    splitLargeStyleSheet(link);
    channel.emit(STORY_RENDERED);
  };

  new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (node.tagName === 'LINK' && node.rel === 'stylesheet') {
          node.addEventListener('load', () => rewriteAgain(node), { once: true });
        }
      });
    });
  }).observe(document, { childList: true, subtree: true });
}

// Angular's state-matrix stories declare which elements the addon should force
// into a pseudo state with `parameters.pseudo`, e.g.
//
//   parameters: { pseudo: { hover: "#Hover", active: "#Active", focusVisible: "#Focus" } }
//
// Blast's @storybook directive can't carry that: GenerateStories reads a fixed
// set of keys (name, status, layout, args, argTypes, design, order, viewMode,
// assetGroup, preset) and has no `parameters` passthrough. `args` is the one
// key that reaches the story untouched, so a matrix story opts in with
//
//   'args' => ['pseudoStates' => true],
//   'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
//
// and this decorator translates that into the parameter the addon reads. It
// runs before the addon's own decorator, so the mutation is visible to it.
// The argTypes entry keeps the flag out of the Controls table.
//
// `true` means the default map below, whose selectors match the
// `id="{{ $state }}"` a matrix story puts on the elements of its Hover /
// Active / Focus rows. A story whose Angular counterpart targets something
// else — two ids per row, or a descendant of the row wrapper — passes the map
// itself instead:
//
//   'args' => ['pseudoStates' => [
//       'hover' => ['#Hover-start', '#Hover-end'],
//       ...
//   ]],
//
// Stories without the flag are untouched, so their pseudo-state toolbar stays
// off.
const DEFAULT_PSEUDO = {
  hover: '#Hover',
  active: '#Active',
  focusVisible: '#Focus'
};

const withPseudoStates = (StoryFn, context) => {
  const pseudoStates = context.args.pseudoStates;

  if (pseudoStates) {
    context.parameters.pseudo =
      pseudoStates === true ? DEFAULT_PSEUDO : pseudoStates;
  }

  return StoryFn();
};

let setDocsTheme = (configDocsTheme) => {
  if (configDocsTheme === 'dark') {
    return themes.dark;
  } else if (configDocsTheme === 'custom') {
    return theme;
  } else {
    return themes.normal;
  }
};

const customViewports = JSON.parse(process.env.STORYBOOK_VIEWPORTS);

// Story order inside a component: `{ <story title>: [<story name>, ...] }`,
// collected from the generated .stories.json files by main.js. See the comment
// there for why the sequence has to be restored at all.
const storyOrder = JSON.parse(process.env.STORYBOOK_STORY_ORDER || '{}');

// The v6 story store hands a comparator its internal tuples rather than the
// story objects; v7 and the docs index hand over the objects themselves.
const storyOf = (entry) => (Array.isArray(entry) ? entry[1] : entry);

// Unlisted names (the autodocs "Docs" node) sort first, as they do by default.
const indexOf = (story) => {
  const names = storyOrder[story.title];
  return names ? names.indexOf(story.name) : -1;
};

// Storybook's own storySort walk over `order`, used for everything above the
// story level. Kept verbatim in behaviour: a nested array applies to the entry
// before it, "*" is the wildcard, and unlisted siblings keep index order.
const TITLE_SEPARATOR = /\s*\/\s*/;

const compareTitles = (titleA, titleB, configuredOrder) => {
  const pathA = titleA.trim().split(TITLE_SEPARATOR);
  const pathB = titleB.trim().split(TITLE_SEPARATOR);
  let order = configuredOrder;

  for (let depth = 0; pathA[depth] || pathB[depth]; depth += 1) {
    if (!pathA[depth]) return -1;
    if (!pathB[depth]) return 1;

    const nameA = pathA[depth];
    const nameB = pathB[depth];

    if (nameA !== nameB) {
      const wildcard = order.indexOf('*');
      let indexA = order.indexOf(nameA);
      let indexB = order.indexOf(nameB);

      if (indexA === -1 && indexB === -1) return 0;
      if (indexA === -1) indexA = wildcard !== -1 ? wildcard : order.length;
      if (indexB === -1) indexB = wildcard !== -1 ? wildcard : order.length;

      return indexA - indexB;
    }

    let index = order.indexOf(nameA);
    if (index === -1) index = order.indexOf('*');
    order = index !== -1 && Array.isArray(order[index + 1]) ? order[index + 1] : [];
  }

  return 0;
};

const configuredOrder = JSON.parse(process.env.STORYBOOK_SORT_ORDER || '[]');

const sortStories = (a, b) => {
  const storyA = storyOf(a);
  const storyB = storyOf(b);

  if (storyA.title === storyB.title) return indexOf(storyA) - indexOf(storyB);

  return compareTitles(storyA.title, storyB.title, configuredOrder);
};

const preview = {
  parameters: {
    viewport: {
      viewports: customViewports
    },
    controls: {
      expanded: JSON.parse(process.env.STORYBOOK_EXPANDED_CONTROLS)
    },
    server: {
      url: process.env.STORYBOOK_SERVER_URL
    },
    layout: 'padded',
    status: {
      statuses: JSON.parse(process.env.STORYBOOK_STATUSES)
    },
    docs: {
      extractComponentDescription: (component, { notes }) => {
        if (notes) {
          return typeof notes === 'string'
            ? notes
            : notes.markdown || notes.text;
        }
        return null;
      },
      theme: setDocsTheme(JSON.parse(process.env.STORYBOOK_DOCS_THEME)),
      // Render docs stories directly in the docs document instead of each in
      // its own nested <iframe>.
      //
      // Blast serves every story as a full HTML document whose <head> links
      // /vendor/tedi/tedi.css, so the iframe default meant one Storybook
      // preview — and one long-lived /__webpack_hmr EventSource — per story.
      // Past six stories those SSE streams exhaust Chrome's per-host HTTP/1.1
      // connection pool, and every queued tedi.css / tedi.js request behind
      // them never completes: the docs page renders unstyled. Inline rendering
      // collapses that to a single preview, so the assets load once.
      //
      // It also drops the fixed 100px iframe height that was clipping the
      // taller stories.
      story: {
        inline: true
      }
    },
    options: {
      storySort: sortStories
    }
  },
  globalTypes: JSON.parse(process.env.STORYBOOK_GLOBAL_TYPES),
  decorators: [withPseudoStates]
};

export default preview;
