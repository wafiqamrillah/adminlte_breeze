import './bootstrap';

// Navigation variables
window.nav = {
    width: 990,
    make() {
        return {
            collapsed : window.innerWidth < this.width,
            resize(){
                this.collapsed = window.innerWidth < this.width;
            },
            toggle() {
                this.collapsed = !this.collapsed;
                if (!this.collapsed) {
                    this.$refs.body.classList.add('sidebar-open');
                } else {
                    this.$refs.body.classList.remove('sidebar-open');
                }
            },
            clickAway() {
                if (window.innerWidth < this.width) {
                    this.$refs.body.classList.remove("sidebar-open");
                    this.collapsed = true;
                }
            }
        }
    }
}