import Vue from 'vue'
const DOMParser = process.browser ? window.DOMParser : require('xmldom').DOMParser
const parser = new DOMParser()

Vue.component('svg-inline', {
    props: {
        src: {
            type: String,
            required: true
        }
    },
    render(h) {
        const document = parser.parseFromString(this.src, 'text/html')
        const svg = document.getElementsByTagName('svg')[0]
        const attrs = {}


        if (svg) {
            const box = svg.getAttribute('viewBox');
            const viewBox = box.split(/\s+|,/);

            Array.prototype.forEach.call(svg.attributes, (attribute) => {
                attrs[attribute.nodeName] = attribute.nodeValue
                attrs['width'] = viewBox[2];
                attrs['height'] = viewBox[3];

                if (this.class) {
                    attrs['class'] = this.class;
                }
            });

            return h('svg', {
                ...this.data,
                attrs,
                domProps: {
                    innerHTML: svg.innerHTML
                }
            })
        }

    }
})
