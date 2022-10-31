<template>
    <div
        class="Roadmap"
        id="roadmap">
        <div class="Roadmap-holder">
            <h2 class="Roadmap-title">{{ $t('home.roadmap.title') }}</h2>
            <p class="Roadmap-description">
                {{ $t('home.roadmap.description') }}
            </p>
        </div>


        <div class="Roadmap-map">
            <svg-inline src="~/assets/vector/inline/roadmap.svg"/>
        </div>

    </div>
</template>

<script>
    export default {
        name: 'Roadmap',
        serverCacheKey () {
            return 'roadmap'
        },
        mounted() {
            this.interactions();
        },
        methods: {
            interactions() {
                const elements = SVG.select("g.point");

                elements.each(function() {
                    const circle = this.select(".circle");
                    const rect = this.select(".rect");
                    const image = this.select("image");
                    const text = this.select("text");
                    rect.on("mouseover", function() {
                        circle
                            .animate(200)
                            .transform({ scale: 2 })
                            .stroke({ color: "#fff", opacity: 0.5, width: 5 });
                        image.animate(200).opacity(1);
                        text.animate(200).opacity(1);
                    });

                    rect.on("mouseout", function() {
                        circle
                            .animate(200)
                            .transform({ scale: 1 })
                            .stroke({ color: "#fff", opacity: 0, width: 5 });
                        image.animate(200).opacity(0);
                        text.animate(200).opacity(0);
                    });
                });
            }
        }
    };
</script>

