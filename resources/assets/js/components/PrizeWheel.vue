<template>
  <v-container>
    <br><br><br>
      <center>
        <v-card max-width="500" outlined class="px-5 pt-5" elevation="2">
          <v-card-text>
            <p
              class="text-center headerNames display-4 reward black--text font-weight-black"
            >{{headerNames}}</p>
            <br>
            <div class="text-center">
              <v-btn
                color="green"
                class="mx-5"
                :disabled="points <= fee"
                v-if="!loading"
                outlined
                @click="dialog = true"
              >Start</v-btn>
              <v-btn color="red" v-else outlined @click="handleStop">Stop</v-btn>
              <v-btn color="primary" v-if="!loading" outlined @click="handleReset">Reset</v-btn>
              <br>
              <br>
              <p
                class="title font-weight-black"
              >{{points >= fee ? `Points available : ${points}`:'Insufficient Points'}}</p>
              <p class="title font-weight-black">Next Draw : {{fee}}</p>
            </div>
          </v-card-text>
          <v-dialog v-model="dialog" max-width="350">
            <v-card class="px-5 py-5">
              <v-card-text class="headline">Do you want to draw for {{fee}} points?</v-card-text>
              <div class="text-center">
                <v-btn color="red darken-1" class="mx-2" dark @click="dialog = false">Disagree</v-btn>
                <v-btn color="green darken-1" dark @click="handleStart">confirm</v-btn>
              </div>
            </v-card>
          </v-dialog>
        </v-card>
        <v-card max-width="500" outlined class="mt-5 px-2 pt-5" elevation="2">
          <p class="text-center font-weight-black text-uppercase display-2">Rewards</p>
          <v-list v-if="rewards.length">
            <v-list-item v-for="(reward , x) in rewards" :key="x">
              <v-list-item-content>
                <v-card
                  class="mx-auto"
                  elevation="2"
                  color="amber lighten-2"
                  max-width="400"
                  outlined
                >
                  <v-list-item>
                    <v-list-item-avatar size="80" color="grey darken-2">
                      <span class="white--text display-2">?</span>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title class="display-1 mb-1 font-weight-black">{{reward.secret}}</v-list-item-title>
                      <v-list-item-subtitle>
                        <v-btn outlined small @click="claim(reward)">claim reward</v-btn>
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </v-list-item-content>
            </v-list-item>
          </v-list>
          <p class="text-center" v-else>No rewards Yet</p>
        </v-card>
      </center>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      dialog: false,
      headerNames: "?",
      fee: 2,
      index: 0,
      loading: false,
      points: 100,
      rewards: [],
      namesList: [
        {
          secret: Math.random()
            .toString(36)
            .substring(7),
          name: "Voucher 1",
          prize: null
        },
        {
          secret: Math.random()
            .toString(36)
            .substring(7),
          name: "Voucher 2",
          prize: 10
        },
        {
          secret: Math.random()
            .toString(36)
            .substring(7),
          name: "Voucher 3",
          prize: 5
        },
        {
          secret: Math.random()
            .toString(36)
            .substring(7),
          name: "Voucher 4",
          prize: 20
        },
        {
          secret: Math.random()
            .toString(36)
            .substring(7),
          name: "Voucher 5",
          prize: 100
        }
      ],
      vouchers: [{}],
      intervalHandle: null
    };
  },
  methods: {
    handleStart() {
      this.dialog = false;
      this.points -= this.fee;
      this.fee += this.fee * 1.75;
      this.loading = true;
      this.intervalHandle = setInterval(() => {
        this.headerNames = this.namesList[
          this.index++ % this.namesList.length
        ].secret;
      }, 50);
    },
    handleStop() {
      this.rewards.push(this.namesList[this.index++ % this.namesList.length]);
      this.loading = false;
      clearInterval(this.intervalHandle);
    },
    handleReset() {
      this.headerNames = "?";
      this.index = 0;
    },
    claim(reward){
      alert(reward.prize)
    }
  }
};
</script> 
<style lang="scss" scoped>
.reward {
  font-family: "Georgia, Times New Roman, Times, serif" !important;
}
.headerNames {
  font-size: 6rem !important;
}
</style>