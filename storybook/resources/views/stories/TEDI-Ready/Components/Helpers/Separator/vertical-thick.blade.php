@storybook([
    'name' => 'Vertical Thick',
    'order' => 6,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

<div style="display:flex;height:7rem">
    <div style="padding:1rem">12.12.2012</div>
    <tedi:separator axis="vertical" :spacing="2" :thickness="2" />
    <div style="padding:1rem">
        <h6>Title</h6>
        <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit.
            Exercitationem rem nisi quae? Rem, amet! Veritatis laboriosam consectetur ipsum quae.
            Amet voluptatibus quod eaque at nostrum id provident? Cum, maiores libero!
        </p>
    </div>
</div>
