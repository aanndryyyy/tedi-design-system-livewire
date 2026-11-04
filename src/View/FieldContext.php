<?php

namespace Tedi\Livewire\View;

use Tedi\Livewire\Tedi;

/**
 * The Blade stand-in for Angular's `TEDI_FIELD_CONTEXT`
 * (angular/src/tedi/components/form/form-field/field-context.token.ts), plus
 * the registration slot for the other half of the contract,
 * `contentChild(TEDI_FORM_FIELD_CONTROL)`.
 *
 * Two kinds exist, and the nearest one wins, as with Angular's injector:
 *
 *   - formField(): built by `tedi:form-field` (View\Components\FormField)
 *     before its slot renders. Its data frame carries it under the key
 *     `fieldContext`, so a control in the slot finds the nearest one with
 *     `$__env->getConsumableComponentData('fieldContext')`, the public call
 *     `@aware` makes. The first control to call Tedi::fieldContext() registers
 *     itself here (register()); the form-field's view renders after its slot
 *     and reads the registration back from `$control`.
 *   - child(): Angular's date field `childContext`, whose `ownsSurface` is
 *     always true. `tedi:date-field` and `tedi:date-time-field` build one and
 *     hand it down EXPLICITLY, as the `field-context` prop, through
 *     `tedi:date-input` to its text field. A control in a component's own
 *     template cannot find that component through the stack, so nothing finds
 *     it implicitly.
 *
 * It is mutable only through register() and id(); everything else is fixed
 * when the provider builds it.
 */
final class FieldContext
{
    /**
     * The registered control: the first control inside that called
     * Tedi::fieldContext() with a description of itself.
     *
     * @var array{reset?: bool, value?: mixed, disabled?: bool, readOnly?: bool, invalid?: bool}|null
     */
    public ?array $control = null;

    /** Base id shared by the character counter and the control's aria-describedby. */
    private ?string $id = null;

    private function __construct(
        /** 'form-field' | 'date-input' (a date field's child context). */
        public readonly string $provider,
        /** small|default|large, normalised. */
        public readonly string $size,
        /** The form-field's `icon`, as written. */
        public readonly mixed $icon,
        public readonly bool $clearable,
        /** The form-field's `characterLimit`, as written (null: no counter). */
        public readonly mixed $characterLimit,
        /** The form-field's explicit `characterCount`, as written (null: count the value). */
        public readonly mixed $characterCount,
        public readonly bool $valid,
        /** The form-field's `disabled`, or an enclosing component's (`@aware` semantics). */
        public readonly bool $disabled,
        /** The form-field's `invalid`, or an enclosing component's (`@aware` semantics). */
        public readonly bool $invalid,
    ) {}

    /** A form-field's context, from its resolved props. */
    public static function formField(
        mixed $size = 'default',
        mixed $icon = null,
        bool $clearable = true,
        mixed $characterLimit = null,
        mixed $characterCount = null,
        bool $valid = false,
        bool $disabled = false,
        bool $invalid = false,
    ): self {
        return new self('form-field', Tedi::fieldSize($size), $icon, $clearable, $characterLimit, $characterCount, $valid, $disabled, $invalid);
    }

    /**
     * Angular's date field `childContext`: the date field paints the surface,
     * never the input inside it, and nothing inside it asks a form-field for a
     * clear button (the date field owns its own).
     */
    public static function child(mixed $size = 'default', bool $invalid = false, bool $valid = false, bool $disabled = false, bool $clearable = true): self
    {
        return new self('date-input', Tedi::fieldSize($size), null, $clearable, null, null, $valid, $disabled, $invalid);
    }

    /**
     * Register a control (Angular's `contentChild(TEDI_FORM_FIELD_CONTROL)`).
     * Only the first control registers; an empty description registers
     * nothing (`tedi:form.label` reads the context without being a control).
     *
     * @param  array{reset?: bool, value?: mixed, disabled?: bool, readOnly?: bool, invalid?: bool}  $control
     */
    public function register(array $control): void
    {
        if ($control !== [] && $this->control === null) {
            $this->control = $control;
        }
    }

    /** The base id the character counter and the control's aria-describedby share. */
    public function id(): string
    {
        return $this->id ??= Tedi::id('tedi-form-field');
    }

    /**
     * hasBox(): the field's box paints the surface, not the control. It has
     * one for an icon, or for a clear button, which only a control that
     * registered `reset` earns. The REGISTERED control decides, as it does in
     * form-field.blade.php, so a second control agrees with the box drawn.
     */
    public function ownsSurface(): bool
    {
        if ($this->provider !== 'form-field') {
            return true;
        }

        return ! empty($this->icon) || ($this->clearable && ! empty($this->control['reset']));
    }

    /** Whether the registered control's value exceeds the character limit. */
    public function characterLimitExceeded(): bool
    {
        if ($this->characterLimit === null) {
            return false;
        }

        $value = $this->control['value'] ?? '';
        // Only a string value has a length to count; a date field in
        // `multiple` / `range` mode registers an array.
        $count = $this->characterCount ?? (is_scalar($value) ? mb_strlen((string) $value) : 0);

        return $count > $this->characterLimit;
    }

    /**
     * What a control reads:
     *   provider     'form-field' | 'date-input'
     *   size         'small' | 'default' | 'large'
     *   ownsSurface  bool — the field's box paints the surface, not the control
     *   invalid      bool — the field's `invalid` (or an enclosing input
     *                group's), or an exceeded character limit
     *   valid        bool
     *   disabled     bool — the field's `disabled`, or an enclosing input group's
     *   clearable    bool
     *   describedBy  string[] — ids the control adds to its aria-describedby
     *
     * @return array{provider: string, size: string, ownsSurface: bool, invalid: bool, valid: bool, disabled: bool, clearable: bool, describedBy: list<string>}
     */
    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'size' => $this->size,
            'ownsSurface' => $this->ownsSurface(),
            'invalid' => $this->invalid || $this->characterLimitExceeded(),
            'valid' => $this->valid,
            'disabled' => $this->disabled,
            'clearable' => $this->clearable,
            'describedBy' => $this->characterLimit !== null ? [$this->id().'-character-count'] : [],
        ];
    }
}
