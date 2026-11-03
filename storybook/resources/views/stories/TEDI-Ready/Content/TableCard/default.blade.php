@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.68.85?node-id=53155-151142&m=dev',
    'args' => [
        'title' => '',
        'subtitle' => '',
        'titleElement' => 'h3',
        'layout' => 'horizontal',
        'columns' => 1,
        'labelSize' => 'small',
        'labelAlign' => '',
        'valueAlign' => '',
        'rowAlign' => 'start',
        'collapsible' => false,
        'defaultOpen' => true,
        'arrowType' => 'default',
        'selectable' => false,
    ],
    'argTypes' => [
        'title' => ['control' => 'text', 'description' => 'Header title. Required for `collapsible`.'],
        'subtitle' => ['control' => 'text', 'description' => 'Secondary line under the title.'],
        'titleElement' => [
            'control' => 'select',
            'options' => ['h2', 'h3', 'h4', 'h5', 'h6'],
            'description' => 'Heading tag for `title` (for the document outline). The title stays body-sized unless `titleModifiers` is set.',
            'table' => ['defaultValue' => ['summary' => 'h3']],
        ],
        'layout' => [
            'control' => 'radio',
            'options' => ['horizontal', 'vertical'],
            'description' => 'Row layout: `horizontal` (label beside value) or `vertical` (label above value).',
            'table' => ['defaultValue' => ['summary' => 'horizontal']],
        ],
        'columns' => [
            'control' => 'number',
            'description' => 'Number of equal-width columns. Best with `layout="vertical"`.',
            'table' => ['defaultValue' => ['summary' => '1']],
        ],
        'labelSize' => [
            'control' => 'radio',
            'options' => ['small', 'default'],
            'description' => 'Label text size — `small` uses the 14px label style, `default` the 16px body style.',
            'table' => ['defaultValue' => ['summary' => 'default']],
        ],
        'labelAlign' => [
            'control' => 'select',
            'options' => ['', 'left', 'right'],
            'description' => 'Label text alignment. Defaults to `right` in horizontal layout, `left` otherwise.',
        ],
        'valueAlign' => [
            'control' => 'select',
            'options' => ['', 'left', 'right'],
            'description' => 'Value text alignment. Defaults to `right` in horizontal layout, `left` otherwise.',
        ],
        'rowAlign' => [
            'control' => 'radio',
            'options' => ['start', 'center'],
            'description' => 'Vertical alignment of each label against its value (horizontal layout only).',
            'table' => ['defaultValue' => ['summary' => 'start']],
        ],
        'collapsible' => [
            'control' => 'boolean',
            'description' => 'Make the header a toggle that shows / hides the body.',
            'table' => ['defaultValue' => ['summary' => 'false']],
        ],
        'defaultOpen' => [
            'control' => 'boolean',
            'description' => 'Initial open state.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
        'arrowType' => [
            'control' => 'radio',
            'options' => ['default', 'secondary'],
            'description' => 'Style of the collapse indicator (only applies when `collapsible`).',
            'table' => ['defaultValue' => ['summary' => 'default']],
        ],
        'selectable' => [
            'control' => 'boolean',
            'description' => 'Show a leading checkbox to select the row.',
            'table' => ['defaultValue' => ['summary' => 'false']],
        ],
    ],
])

@php
    $benefitRows = [
        ['label' => 'Hüvitise arvutamine', 'value' => 'Tervisekassa ei hüvita'],
        ['label' => 'Kogus', 'value' => '2 päeva'],
        ['label' => 'Ühe päeva hüvitis (€)', 'value' => '-'],
        ['label' => 'Summa (€)', 'value' => '0.00 €', 'bold' => true],
    ];
@endphp

<div style="max-width: 360px">
    <tedi:table-card
        :rows="$benefitRows"
        :summary="['label' => 'Ülekande summa', 'value' => '0.00 €']"
        :title="$title ?: null"
        :subtitle="$subtitle ?: null"
        :title-element="$titleElement"
        :layout="$layout"
        :columns="(int) $columns"
        :label-size="$labelSize"
        :label-align="$labelAlign ?: null"
        :value-align="$valueAlign ?: null"
        :row-align="$rowAlign"
        :collapsible="(bool) $collapsible"
        :default-open="(bool) $defaultOpen"
        :arrow-type="$arrowType"
        :selectable="(bool) $selectable"
    />
</div>
