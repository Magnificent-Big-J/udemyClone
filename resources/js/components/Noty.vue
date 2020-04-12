<template>

    <div class="alert  alert-noty" :class="type" v-if="notification">
        <p class="text-center">{{notification.message}}</p>
    </div>

</template>

<script>
    export default {
        name: "Noty",
        data(){
            return {
                notification: null
            }
        },
        created() {
            window.events.$on('notification', (payload) => {
                this.notification = payload
                setTimeout(()=>{
                    this.notification = null
                }, 250)
            })
        },
        computed: {
            type() {
                return `alert-${this.notification.type}`
            }
        }
    }
</script>

<style scoped>
.alert-noty {
    position: fixed;
    right: 20px;
    bottom: 40px;
    z-index: 1;
}
</style>
