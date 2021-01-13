<template>
  <header class="hero">
    <div class="center-content">
      <h1>Pizza_Mart</h1>
      <br>
      <h3>Enjoy Your Pizza to the fullest</h3>
         
      <v-btn
          depressed
          x-large
          rounded
          href="#products"
          width="200"
          color="danger white--text"
        >
          Start
        </v-btn>
    <br><br>
        <p  v-if="!$app.user" class="banner-text"><span class="white--text">Grow your Pizza business with us!</span> <router-link class="underlined"  to="/pizzamart/register/store">Sign up here.</router-link></p>
    </div>
  </header>
</template>
<script>
export default {
  data(){
    return{
      address:""
    }
  },

}
</script>


<style lang="sass" scoped>
.hero
  background:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url('/images/banner.jpg')
  background-size: cover
  background-position: center
  width: 100wh
  height: 100vh
  display: flex

.center-content
  width: 400px
  display: flex
  flex-direction: column
  align-items: center
  margin: auto
  
  & h1, h3
    color: white
    line-height: 1
  
  & h1
    text-transform: uppercase
    font-size: 3em
    margin-bottom: 0
    text-align: center
    
  & h3
    margin-bottom: 40px
    font-size: 1.5em
    font-weight: normal

.button
  text-transform: uppercase
  color: white
  padding: 20px
      text-decoration: none  
  
  &:hover
    background-color: rgba(255, 255, 255, 0.1)
    border-color: rgba(255, 255, 255, 0.7)
    color: white
</style>

