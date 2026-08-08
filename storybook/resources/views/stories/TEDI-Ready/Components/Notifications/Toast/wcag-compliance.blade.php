{{--
    The `role` prop is a genuine, portable rendering difference (see
    toast.blade.php / alert.blade.php's aria-live mapping) — the ToastService
    trigger buttons from the Angular story are dropped per default.blade.php's
    reasoning, but the three role variants are rendered statically.
--}}
@storybook([
    'name' => 'WCAG Compliance',
    'order' => 7,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap="3">
    <tedi:toast type="success" role="status" title="Success">Screen reader announces politely</tedi:toast>
    <tedi:toast type="danger" role="alert" title="Error">Screen reader announces immediately</tedi:toast>
    <tedi:toast type="info" role="none" title="Info">No screen reader announcement</tedi:toast>
</tedi:row>
