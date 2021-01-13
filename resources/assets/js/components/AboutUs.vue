<template>
<div class="pt-12">
    <v-container
        fluid
        style="background:linear-gradient(rgba(255,255,255,0.6),rgba(255,255,255,0.6)), url('https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-1.2.1&w=1000&q=80');background-repeat:no-repeat;background-size:cover;background-attachment:fixed;background-position:center"
    >   
    <br><br>
    <p class="text-center display-2 ">About Us</p>
        <v-timeline align-top :dense="$vuetify.breakpoint.smAndDown">
        <v-timeline-item
            v-for="(dev, i) in devs"
            :key="i"
            :color="dev.color"
            :icon="dev.icon"
            fill-dot
        >
            <v-card :color="dev.color" dark hover elevation="2">
            <v-card-title class="title text-center">{{dev.name}}</v-card-title>
            <v-card-text class="px-6 pt-5 white text--primary">
                <v-row no-gutters>
                <v-col key="1" cols="12">
                    <center>
                    <v-avatar size="160" class="px-5 py-5 text-center">
                        <img :src="dev.image" :alt="dev.github">
                    </v-avatar>
                    <br>
                    <p class="px-5 py-5 subtitle-2">{{dev.role}}</p>
                    </center>
                    <p>{{dev.quotes}}</p>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn :color="dev.color" class="mx-0" @click="visit(dev.github)" outlined>
                        <v-icon>mdi-github-circle</v-icon>
                        @{{dev.github}}
                    </v-btn>
                    </v-card-actions>
                </v-col>
                </v-row>
            </v-card-text>
            </v-card>
        </v-timeline-item>
        </v-timeline>
        <v-footer padless>
            <v-card color="danger"></v-card>
        </v-footer>
        <!-- <v-row>
        <v-col v-for="n in 3" :key="n" cols="12" md="4">
            <v-item>
            <v-card color="primary" class="d-flex align-center" dark height="200"></v-card>
            </v-item>
        </v-col>
        </v-row> -->
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    devs: [
      {
        name: "Leonilo Torres",
        role: "Developer / Project Manager",
        github: "QuinYolXD99",
        image:
          "https://www.origin.com/bower_components/origin-components/dist/images/social//avatar_placeholder.png",
        quotes:
          "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum maiores modi quidem veniam, expedita quis laboriosam, ullam facere adipisci, iusto, voluptate sapiente corrupti asperiores rem nemo numquam fuga ab at.",
        color: "purple darken-1",
        icon: "mdi-vuejs"
      },
      {
        name: "Joey Destacamento",
        role: "Developer / Business Enthusiast",
        github: "joeygov",
        image:
          "https://www.origin.com/bower_components/origin-components/dist/images/social//avatar_placeholder.png",
        quotes:
          "Lorem ipsum dolor sit amet, consectetur adipisicing elit. H-sapiente corrupti asperiores rem nemo numquam fuga ab at.",
        color: "orange darken-2",
        icon: "mdi-language-php"
      },
      {
        name: "Chirstopher Alonzo",
        role: "Developer / Lead Backend Developer",
        github: "alchristophergwapo",
        quotes:
          "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum maiores modi quidem veniam, expedita quis laboriosam, ullam facere adipisci, iusto, voluptate sapiente corrupti asperiores rem nemo numquam fuga ab at.",
        image:
          "https://www.origin.com/bower_components/origin-components/dist/images/social//avatar_placeholder.png",
        color: "indigo",
        icon: "mdi-laravel"
      }
    ]
  }),
  methods: {
    visit(name) {
      window.open("https://github.com/" + name);
    }
  }
};
</script>