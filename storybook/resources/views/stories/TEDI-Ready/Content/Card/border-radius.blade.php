@storybook([
    'name' => 'Border Radius',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<tedi:row cols="1" gap="4">
    <tedi:col>
        <tedi:card>
            <tedi:card-content>
                <tedi:text as="p">Vaikimisi nurgaraadius</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :border-radius="false">
            <tedi:card-content>
                <tedi:text as="p">Nurgaraadius puudub</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :border-radius="['top' => false]">
            <tedi:card-content>
                <tedi:text as="p">Ülemine nurgaraadius puudub</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :border-radius="['bottom' => false]">
            <tedi:card-content>
                <tedi:text as="p">Alumine nurgaraadius puudub</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :border-radius="['left' => false]">
            <tedi:card-content>
                <tedi:text as="p">Vasak nurgaraadius puudub</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :border-radius="['right' => false]">
            <tedi:card-content>
                <tedi:text as="p">Parem nurgaraadius puudub</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :border-radius="['topLeft' => false]">
            <tedi:card-content>
                <tedi:text as="p">Ülemine vasak nurgaraadius puudub</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card :border-radius="['bottomRight' => false]">
            <tedi:card-content>
                <tedi:text as="p">Alumine parem nurgaraadius puudub</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
</tedi:row>
