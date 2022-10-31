window.onNuxtReady(() => {

    /**
     * Current URL, without the hash
     */
    var baseUrl = window.location.href
        .replace(window.location.hash, "");

    /**
     *  Find all `use` elements with a namespaced `href` attribute, e.g.
     *  <use xlink:href="#some-id"></use>
     *
     *  See: http://stackoverflow.com/a/23047888/796152
     */
    [].slice.call(document.querySelectorAll("use[*|href]"))

        /**
         * Filter out all elements whose namespaced `href` attribute doesn't
         * start with `#` (i.e. all non-relative IRI's)
         *
         * Note: we're assuming the `xlink` prefix for the XLink namespace!
         */
        .filter(function(element) {
            return (element.getAttribute("xlink:href").indexOf("#") === 0);
        })

        /**
         * Prepend `window.location` to the namespaced `href` attribute value,
         * in order to make it an absolute IRI
         *
         * Note: we're assuming the `xlink` prefix for the XLink namespace!
         */
        .forEach(function(element) {
            element.setAttribute("xlink:href", baseUrl + element.getAttribute("xlink:href"));
    });


    [].slice.call(document.querySelectorAll("rect[*|fill]"))

        /**
         * Filter out all elements whose namespaced `fill` attributes doesn't
         * which doesnt have cross referenced values
         *
         * Note: we're assuming the `url(` prefix for the cross referenced fills !
         */
        .filter(function(element) {
            return (element.getAttribute("fill").indexOf("url(") === 0);
        })

        /**
         * Insert `window.location` to the `fill` attribute value,
         * in order to make it an absolute IRI
         *
         */
        .forEach(function(element) {
            var attrVal = element.getAttribute("fill");
            var idx = attrVal.indexOf("url(");
            element.setAttribute("fill",attrVal.slice(0,idx+4)+baseUrl+ attrVal.slice(idx+4));
    });


})
