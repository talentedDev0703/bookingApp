<template>
    <div
        class="About"
        id="about">
        <div class="container">
            <div class="About-holder u-cf">
                <h2 class="About-title">{{ $t('home.about.title') }}</h2>
                <div class="About-description">
                    <p>
                        {{ $t('home.about.description') }}
                    </p>
                </div>
                <span
                    aria-hidden="true"
                    class="About-birds">
                    <svg-inline
                        src="~/assets/vector/inline/bird.svg"
                        class="About-bird1" />
                    <svg-inline
                        src="~/assets/vector/inline/bird.svg"
                        class="About-bird2" />
                    <svg-inline
                        src="~/assets/vector/inline/bird.svg"
                        class="About-bird3" />
                </span>
            </div>


            <div
                class="AboutList"
                ref="accordion">

                <div
                    class="AboutList-item"
                    v-for="item in $t('home.about.list')"
                    :key="item.title">
                    <div class="AboutList-content">
                        <h2 class="AboutList-title">{{ item.title }}</h2>
                        <p class="AboutList-description">{{ item.description }}</p>
                    </div>
                    <p class="AboutList-image">
                        <v-image
                            :src="require('~/assets/raster/placeholder.jpg')"
                            :datasrc="require(`~/assets/raster/${ item.image }`)"/>
                    </p>
                </div>
            </div>

            <ul class="Features">
                <li
                    v-for="item in $t('home.about.features')"
                    :key="item.title">
                    <span class="Features-icon">
                        <svg-inline :src="svgPath(item.icon)"/>
                    </span>
                    {{ item.title }}
                </li>
            </ul>

        </div>
    </div>
</template>
<script>
    import vImage from '~/components/image.vue'
    export default {
        data() {
            return {
                accordionInited: false
            };
        },
        mounted() {
            if (this.$mq === "sm" || this.$mq === "sm2" || (this.$mq === "md" && !this.accordionInited)) {
                this.accordion();
                this.accordionInited = true;
            }
        },
        watch: {
            mq: function(init) {
                if (init && !this.accordionInited) {
                    this.accordion();
                    this.accordionInited = true;
                } else {
                    this.accordion({ destroy: true });
                    this.accordionInited = false;
                }
            }
        },
        computed: {
            mq() {
                return this.$mq === "md" || this.$mq === "sm" || this.$mq === "sm2";
            }
        },
        methods: {
            svgPath(path) {
                return require(`~/assets/vector/inline/${path}`);
            },
            getContent(elem) {
                let elems = [];
                while (
                    elem.nextElementSibling &&
                    elem.nextElementSibling.tagName !== "H2"
                ) {
                    elems.push(elem.nextElementSibling);
                    elem = elem.nextElementSibling;
                }

                // Delete the old versions of the content nodes
                elems.forEach(node => {
                    node.parentNode.removeChild(node);
                });

                return elems;
            },
            accordion(options = { destroy: false }) {
                // Get all the <h2> headings
                const headings = this.$refs.accordion.querySelectorAll("h2");

                Array.prototype.forEach.call(headings, heading => {
                    // Give each <h2> a toggle button child
                    // with the SVG plus/minus icon
                    if (!options.destroy) {
                        heading.innerHTML = `
                        <button aria-expanded="false">
                            ${heading.textContent}
                            <svg width="21" height="13" viewBox="0 0 21 13" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l9.506 10L20 1.013" stroke="#FBB040" stroke-width="2" fill="none" fill-rule="evenodd"/></svg>
                        </button>
                        `;
                    } else {
                        heading.innerHTML = `
                            ${heading.textContent}
                        `;
                    }

                    if (!options.destroy) {
                        // Assign the contents to be expanded/collapsed (array)
                        let contents = this.getContent(heading);

                        // Create a wrapper element for `contents` and hide it
                        let wrapper = document.createElement("div");
                        wrapper.hidden = true;
                        // Add each element of `contents` to `wrapper`
                        contents.forEach(node => {
                            wrapper.appendChild(node);
                        });

                        // Add the wrapped content back into the DOM
                        // after the heading
                        heading.parentNode.insertBefore(wrapper, heading.nextElementSibling);

                        // Assign the button
                        let btn = heading.querySelector("button");

                        btn.onclick = () => {
                            // Cast the state as a boolean
                            let expanded =
                                btn.getAttribute("aria-expanded") === "true" || false;

                            // Switch the state
                            btn.setAttribute("aria-expanded", !expanded);
                            // Switch the content's visibility
                            wrapper.hidden = expanded;
                        };
                    } else {
                        heading.nextElementSibling.replaceWith(
                            ...heading.nextElementSibling.childNodes
                        );
                    }
                });
            }
        },
        components: {
            vImage
        }
    };
</script>

