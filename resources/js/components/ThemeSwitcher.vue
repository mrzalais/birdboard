<template>
    <div class="flex items-center mr-8">
        <button
            :class="{'border-accent': selectedTheme == 'theme-light' }"
            class="button focus:outline-none"
            style="background: #f5f6f9"
            @click="selectedTheme = 'theme-light'"
        >

        </button>
        <button
            class="button focus:outline-none"
            :class="{'border-accent': selectedTheme == 'theme-dark' }"
            style="background: #222"
            @click="selectedTheme = 'theme-dark'"
        >

        </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selectedTheme: 'theme-light'
            }
        },

        watch: {
            selectedTheme() {
                document.body.className = document.body.className.replace(/theme-\w+/, this.selectedTheme);
            }
        }
    }
</script>
