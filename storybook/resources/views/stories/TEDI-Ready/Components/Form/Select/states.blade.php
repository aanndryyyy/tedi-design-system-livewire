@storybook([
    'name' => 'States',
    'order' => 4,
    'status' => 'subset',
    'args' => ['pseudoStates' => [
        'hover' => '#states-hover .tedi-input',
        'focus' => '#states-focus .tedi-input',
        'active' => '#states-active .tedi-input',
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    The Hover, Focus and Active rows are forced by
    storybook-addon-pseudo-states: the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`. Angular
    targets the control through its wrapper div rather than by id, so this
    story passes that selector map verbatim instead of the default
    `#Hover`/`#Active`/`#Focus` one — `.tedi-input` is on the native <select>
    here too.

    Still a subset: <tedi:select> is the documented native-<select> port
    (CONTRACT.md §5), so this shows the same states on simpler markup.
--}}
<div class="flex flex-column gap-4">
    <tedi:select
        input-id="states-default"
        label="Default"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
    />
    <div id="states-hover">
        <tedi:select
            input-id="states-hover-select"
            label="Hover"
            :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
        />
    </div>
    <div id="states-focus">
        <tedi:select
            input-id="states-focus-select"
            label="Focus"
            :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
        />
    </div>
    <div id="states-active">
        <tedi:select
            input-id="states-active-select"
            label="Active"
            :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
        />
    </div>
    <tedi:select
        input-id="states-error"
        label="Error"
        state="error"
        :feedback-text="['type' => 'error', 'text' => 'Error text']"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
    />
    <tedi:select
        input-id="states-valid"
        label="Valid"
        state="valid"
        :feedback-text="['type' => 'valid', 'text' => 'Valid text']"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
    />
    <tedi:select
        input-id="states-disabled"
        label="Disabled"
        :disabled="true"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
    />
</div>
