<script>
    export default {
        bind(el, binding, vnode) {
            $(window).scroll(function() {
                if ($(window).scrollTop() >= 56) {
                    $(el).addClass('fixed-header');
                } else {
                    $(el).removeClass('fixed-header');
                }
            });
        }
    }
</script>