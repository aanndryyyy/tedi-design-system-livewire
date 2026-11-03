<?php

namespace Tedi\Livewire\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\View\Factory;
use Tedi\Livewire\View\FieldContext;

/**
 * `tedi:form-field` — the library's one class component (CONVENTIONS.md §5,
 * "The form-field context"). Its view, the markup and the Angular port notes,
 * is resources/views/components/form-field.blade.php.
 *
 * It is a class only so that it can build its field context BEFORE its slot
 * renders: the constructor runs before the slot, and the context travels in
 * this component's data frame under the key `fieldContext`, where a control
 * in the slot finds it with the same public lookup `@aware` uses.
 *
 * The props the context reads are constructor parameters, nullable so that
 * "not written" stays distinguishable from a written value; data() puts only
 * the WRITTEN ones in the frame, as an anonymous component would, so a
 * descendant's `@aware(['disabled' => …])` sees exactly what it saw before.
 * The multi-word props are constructor parameters too, because `@props` in a
 * class component's view cannot map a kebab-case attribute
 * (`character-limit`) to its camelCase variable.
 *
 * The view factory is injected, as `viewFactory`: Laravel resolves class
 * components through the container and strips every constructor parameter
 * name from the attribute bag, so the name is chosen not to collide with an
 * attribute a consumer would write.
 */
class FormField extends Component
{
    /** The constructor parameters, which never reach the attribute bag. */
    public const PROPS = [
        'size', 'icon', 'clearable', 'showClearOnInteraction', 'inputClass',
        'characterLimit', 'characterCount', 'disabled', 'invalid', 'valid', 'clearAttributes',
    ];

    /** Read by every control inside; see FieldContext. */
    public FieldContext $fieldContext;

    /** Show the clear button only while the filled field is hovered or focused. Requires `clearable`. */
    public bool $showClearOnInteraction;

    /** Deprecated upstream: extra class on the field box. Style the control directly instead. */
    public mixed $inputClass;

    /**
     * Extra attributes forwarded to the clear button (e.g. wire:click).
     *
     * @var array<string, mixed>|\Illuminate\View\ComponentAttributeBag
     */
    public mixed $clearAttributes;

    /** The context props the consumer wrote, for descendants' `@aware`. */
    private array $written;

    /** Private, so it never becomes view data. */
    private Factory $viewFactory;

    public function __construct(
        Factory $viewFactory,
        /** default|small|large — the label, the control and the box row scale together. Default 'default'. */
        mixed $size = null,
        /** Material Symbols icon name, or an array: name/size/color/type/variant. */
        mixed $icon = null,
        /** Shows a clear button once the control holds a value. Read by every control inside. Default true. */
        mixed $clearable = null,
        mixed $showClearOnInteraction = null,
        mixed $inputClass = null,
        /** Maximum character count; shows a live counter and forces the error state past it. */
        mixed $characterLimit = null,
        /** Character count for the counter. Defaults to the length of the control's value. */
        mixed $characterCount = null,
        /** Default: an enclosing component's `disabled` (`@aware`), else false. */
        mixed $disabled = null,
        /** Default: an enclosing component's `invalid` (`@aware`), else false. */
        mixed $invalid = null,
        mixed $valid = null,
        mixed $clearAttributes = null,
    ) {
        $this->written = array_filter(
            compact('size', 'icon', 'clearable', 'characterLimit', 'characterCount', 'disabled', 'invalid', 'valid'),
            fn ($value) => $value !== null,
        );

        // `@aware(['disabled' => false, 'invalid' => false])`: own value, else
        // the nearest enclosing component's (an input group), else false. This
        // frame is not on the stack yet, so the lookup starts at the parent.
        $this->viewFactory = $viewFactory;
        $disabled ??= $viewFactory->getConsumableComponentData('disabled', false);
        $invalid ??= $viewFactory->getConsumableComponentData('invalid', false);

        $this->fieldContext = FieldContext::formField(
            size: $size ?? 'default',
            icon: $icon,
            clearable: (bool) ($clearable ?? true),
            characterLimit: $characterLimit,
            characterCount: $characterCount,
            valid: (bool) ($valid ?? false),
            disabled: (bool) $disabled,
            invalid: (bool) $invalid,
        );

        $this->showClearOnInteraction = (bool) $showClearOnInteraction;
        $this->inputClass = $inputClass;
        $this->clearAttributes = $clearAttributes ?? [];
    }

    /**
     * The frame data: the public properties plus the written context props,
     * the way an anonymous component's frame holds what the consumer wrote.
     */
    public function data()
    {
        return array_merge(parent::data(), $this->written);
    }

    public function render(): View
    {
        return $this->viewFactory->make('tedi::components.form-field');
    }
}
