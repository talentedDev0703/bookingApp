<template>
    <nav
        class="BookingProgress">
        <ul>
            <li
                v-for="(step, index) in steps"
                :key="index"
                :class="{'is-line': step.title === 'line'}"
                :aria-hidden="step.title === 'line'">
                <button
                    v-if="step.title !== 'line'"
                    type="button"
                    class="Button Button--progress"
                    :class="{'is-active': isActive(step.number)}"
                    @click="isAvailable(step.number) ? handleStepChange(step.number) : ''"
                >
                    <span
                        class="Button-number"
                        v-if="step.number">{{ step.number }}</span>
                    <span class="Button-title">{{ step.title }}</span>
                </button>
            </li>
        </ul>
    </nav>
</template>
<script>
    export default {
        data() {
            return {
                steps: [
                    {
                        title: this.$t("booking.steps.1.title"),
                        number: 1
                    },
                    {
                        title: 'line',
                    },
                    {
                        title: this.$t("booking.steps.2.title"),
                        number: 2
                    },
                    {
                        title: 'line'
                    },
                    {
                        title: this.$t("booking.steps.3.title"),
                        number: 3
                    },
                    {
                        title: 'line'
                    },
                    {
                        title: this.$t("booking.steps.4.title"),
                        number: 4
                    }
                ]
            }
        },
        created() {
            if (this.isB2b) {
                this.steps =  [
                    {
                        title: this.$t("booking.steps.1.title"),
                        number: 1
                    },
                    {
                        title: 'line',
                    },
                    {
                        title: this.$t("booking.steps.2.title"),
                        number: 2
                    },
                    {
                        title: 'line'
                    },
                    {
                        title: this.$t("booking.steps.4.title"),
                        number: 3
                    }
                ]
            }
        },
        methods: {
            isActive(index) {
                return index === this.currentStep || this.currentStep >= index;
            },
            isAvailable(index) {
                return index <= this.availableSteps && index !== 3;
            }
        },
        props: {
            isB2b: {
                type: Boolean,
                required: false,
                default: false
            },
            currentStep: {
                type: Number,
                required: true
            },
            availableSteps: {
                type: Number,
                required: true
            },
            handleStepChange: {
                type: Function,
                required: false,
                default: () => {}
            }
        }
    };
</script>
