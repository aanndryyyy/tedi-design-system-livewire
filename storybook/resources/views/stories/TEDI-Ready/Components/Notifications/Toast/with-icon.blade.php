{{--
    Angular's WithIcon story triggers `ToastService.info(..., { icon: "info" })`
    via a button. Static equivalent per default.blade.php's reasoning.
--}}
@storybook([
    'name' => 'With Icon',
    'order' => 2,
    'status' => 'subset',
])

<tedi:toast type="info" icon="info" title="With Icon">Using a custom icon</tedi:toast>
