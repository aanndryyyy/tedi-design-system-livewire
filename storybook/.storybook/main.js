import { existsSync, readdirSync, readFileSync } from 'fs';
import { join } from 'path';

// --- Story order inside a component ------------------------------------
//
// Blast writes the stories of one component into a single .stories.json, in
// the sequence given by the `order` key of each @storybook directive — which
// this port keeps equal to the export order of the Angular story file. The
// @storybook/server preset then compiles that JSON into a CSF module, one
// `export const` per story, in the same sequence.
//
// That sequence is then lost: the CSF module is consumed as an ES module
// namespace object, and the spec makes those enumerate their keys sorted, so
// Storybook sees the stories alphabetically. CSF's escape hatch is
// `export const __namedExportsOrder`, which the server preset's JSON loader
// doesn't emit.
//
// So collect the intended order here, in Node, and hand it to the preview as
// an env var — every STORYBOOK_-prefixed one is inlined into the client
// bundle. Reading the JSON from the preview instead isn't an option: those
// files are claimed by the JSON-to-CSF loader, so a require() of them returns
// the compiled module, and bypassing the loader with `!!` also bypasses
// webpack's JSON handling.
//
// Nothing to maintain, but it is read once at boot: a story added or an
// `order` changed while the watcher is running needs a restart to re-sort.
const collectStoryOrder = (dir, order = {}) => {
  for (const entry of readdirSync(dir, { withFileTypes: true })) {
    const path = join(dir, entry.name);

    if (entry.isDirectory()) {
      collectStoryOrder(path, order);
    } else if (entry.name.endsWith('.stories.json')) {
      const json = JSON.parse(readFileSync(path, 'utf8'));
      order[json.title] = json.stories.map((story) => story.name);
    }
  }

  return order;
};

// LIBSTORYPATH and PROJECTPATH both come from blast:launch / blast:publish;
// the relative path is the last resort for a Storybook started by hand. An
// empty map is harmless — preview.js then leaves the order alone.
const storiesPath =
  process.env.LIBSTORYPATH ||
  join(
    process.env.PROJECTPATH || '.',
    'vendor/area17/blast/stories'
  );

process.env.STORYBOOK_STORY_ORDER = JSON.stringify(
  existsSync(storiesPath) ? collectStoryOrder(storiesPath) : {}
);

const config = {
  stories: ['../vendor/area17/blast/stories/**/*.stories.json'],
  // No `staticDirs` here on purpose: Blast launches Storybook with
  // `-s $STORYBOOK_STATIC_PATH` (= this app's `public/`), and the CLI flag wins
  // over config, so story fixtures such as the header logos are already served
  // from `storybook/public/`. Setting it here would be silently ignored.
  addons: ['../vendor/area17/blast/node_modules/@storybook/addon-links/dist',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/actions',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/backgrounds',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/controls',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/docs',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/highlight',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/measure',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/outline',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/toolbars',
'../vendor/area17/blast/node_modules/@storybook/addon-essentials/dist/viewport',
'../vendor/area17/blast/node_modules/@storybook/addon-a11y',
'../vendor/area17/blast/node_modules/@storybook/addon-designs',
'../vendor/area17/blast/node_modules/storybook-source-code-addon',
'../vendor/area17/blast/node_modules/@etchteam/storybook-addon-status',
// Not one of Blast's own addons: installed on top of its node_modules by
// start.sh, which also documents why it has to live there. Version 2.1.1 is
// the last one whose peers accept Blast's Storybook 7.1.1 — 2.1.2+ require
// ^7.4.6 of theming/core-events/manager-api. See preview.js for the
// stylesheet-timing workaround it needs here.
'../vendor/area17/blast/node_modules/storybook-addon-pseudo-states'],
  docs: {
    autodocs: 'tag',
    defaultName: 'Docs'
  },
  features: {
    storyStoreV7: false
  },
  framework: {
    name: '@storybook/server-webpack5',
    options: {
      quiet: true
    }
  }
};

export default config;
