{{--
    Variant matrix for `tedi:file-upload` (angular/src/tedi/components/form/file-upload).

    Harvested by IntegrityTest::test_rendered_matrix_classes_exist_in_stylesheet,
    which asserts every tedi-* class emitted here has a rule in dist/tedi.css.
    Covers: both sizes (own and inherited), disabled, read-only (one and several
    files), empty, the single-name and tag list shapes, an invalid and a loading
    file, error / success / hint, both clear-button branches (`stacked`), and the
    surface owned by a form field's box. The client pipeline's prototype
    template (a danger tag, the clear group, the error text) is part of every
    editable render, so its classes are harvested too; the last case turns on
    every pipeline prop.
--}}
@php
    $twoFiles = [['name' => 'aruanne.pdf'], ['name' => 'vigane.pdf', 'isValid' => false]];
@endphp

@foreach (['default', 'small'] as $size)
    <tedi:file-upload name="failid" :size="$size" :files="$twoFiles" />
    <tedi:file-upload name="failid" :size="$size" :files="$twoFiles" stacked />
@endforeach
<tedi:form-field size="small"><tedi:file-upload name="failid" /></tedi:form-field>
<tedi:form-field icon="attach_file"><tedi:file-upload name="failid" :files="$twoFiles" /></tedi:form-field>
<tedi:file-upload name="failid" :files="[['name' => 'ainus.pdf']]" />
<tedi:file-upload name="failid" multiple :files="[['name' => 'laadib.pdf', 'isLoading' => true]]" />
<tedi:file-upload name="failid" :files="$twoFiles" error="Viga" />
<tedi:file-upload name="failid" :files="$twoFiles" success="Korras" />
<tedi:file-upload name="failid" accept=".pdf,.txt" :max-size="1024" hint="Vihje" />
<tedi:file-upload name="failid" :files="$twoFiles" disabled />
<tedi:file-upload name="failid" :files="$twoFiles" read-only />
<tedi:file-upload name="failid" :files="[['name' => 'ainus.pdf']]" read-only />
<tedi:file-upload name="failid" />
<tedi:file-upload name="failid" multiple keep-rejected-files accept=".pdf" :max-size="1024" validator="(file) => null" />
