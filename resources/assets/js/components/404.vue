<template>
    <v-content>
        <v-container
            style="padding:0"
            class="fill-height"
            fluid
        >
            <v-row
            align="center"
            justify="center"
            style="height:100%!important"
            >
            <v-col
                cols="12"
                md="7"
                class="account-form"
            >
            
                </v-col>
                <v-col
                cols="12"
                md="5"
                >
                <div class="text-center display-2 font-weight-light">
                    <span><h1>Ooops!</h1></span><br>
                    Page not Found
                    <br>
                    <br>
                    <v-btn
                    class='my-3'
                    large
                    to="/"
                    color="danger white--text"
                >
                Back to home
                </v-btn>
                </div>
                </v-col>
            </v-row>
        </v-container>
    </v-content>
</template>

<script>
export default {
    name:"notFound"
}
</script>
