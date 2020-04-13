<template>
    <div>
        <button class="btn btn-success" @click="subscribe('monthly')">Subscribe to R200.00 Monthly</button>
        <button class="btn btn-info" @click="subscribe('yearly')"> Subscribe to R1200.00 Yearly</button>
    </div>
</template>

<script>
    import Swal from 'sweetalert';
    import Axios from 'axios'
    export default {
        name: "Stripe",
        props: ['email'],
        data(){
            return{
                plan: '',
                amount: 0,
                handler: null
            }
        },
        methods:{
            subscribe(plan) {
                if(plan == 'monthly') {
                    window.stripePlan = 'monthly'
                    this.amount = 20000
                } else {
                    window.stripePlan = 'yearly'
                    this.amount = 120000
                }

                this.handler.open({
                    name: 'ELearning',
                    description: 'E-Learning Subscription',
                    amount: this.amount,
                    email: this.email
                })
            }
        },
        mounted() {
            this.handler = StripeCheckout.configure({
                key: 'pk_test_Qrcd020o8YblNexmCuhhOhkw00r9Ri6Bt2',
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                token(token) {
                    Swal({ text: 'Please wait while we subscribe you to a plan ...', buttons: false });
                    Axios.post('/subscribe', {
                        stripeToken: token.id,
                        plan: window.stripePlan
                    }).then(resp => {
                        Swal({ text: 'Successfully subscribed', icon: 'success' })
                            .then(() => {
                                window.location = '';
                            });
                    })
                }
            })
        }

    }
</script>

<style scoped>

</style>
