@storybook([
    'name' => 'Language With Navigation Links',
    'order' => 17,
    'status' => 'stable',
    'args' => [],
])

{{--
    Exercises header.language's `languageHrefs` prop — options render as real
    <a href> anchors instead of client-side switch buttons. Uses hash
    fragments like the Angular story does, so selecting stays within the
    preview.
--}}
<header tedi-header style="all: unset; display: block;">
    <tedi:header.actions>
        <tedi:header.language
            :languages="['et' => 'EST', 'en' => 'ENG', 'ru' => 'RUS']"
            :language-hrefs="['et' => '#et', 'en' => '#en', 'ru' => '#ru']"
        />
    </tedi:header.actions>
</header>
