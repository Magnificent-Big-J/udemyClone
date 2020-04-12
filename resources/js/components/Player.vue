<template>
    <div>
        <div :data-vimeo-id="lesson.video_id" data-vimeo-width="900" v-if="lesson" id="handstick"></div>
    </div>
</template>

<script>
    import Axios from 'axios'
    import Player from '@vimeo/player';
    import Swal from 'sweetalert'
    export default {
        name: "Player",
        props:['default_lesson','next_lesson_url'],
        data(){
            return {
                lesson: JSON.parse(this.default_lesson)
            }
        },
        methods: {
            displayVideoEndedAlert()
            {
                Swal('Yaaay ! You completed this series !')
            }
        }
        ,
        mounted() {
            const player = new Player('handstick')

            player.on('ended', () =>{
                this.displayVideoEndedAlert();
            })
        }
    }
</script>

<style scoped>

</style>
