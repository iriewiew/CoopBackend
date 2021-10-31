<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>QR-Code กิจกรรม {{form.event_name}}</h1>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-btn flat icon @click="print">
          <v-icon>mdi-printer</v-icon>
        </v-btn>
      </v-toolbar-items>
    </v-toolbar>

    <div  ref="content">
      <br>
      <v-container>
<br>
          <qrcode v-bind:value="form.id" :options="{ width: 500 }"/>
          <br>
          <!-- <h1>กิจกรรม {{form.event_name}}</h1> -->
          <h2>ศูนย์สหกิจศึกษามหาวิทยาลัยราชภัฏลำปาง</h2>

      </v-container>
    </div>
  </v-app>
</template>
<script>
import axios from "axios";
import jsPDF from "jspdf";
import html2canvas from "html2canvas";
export default {
  mounted() {
    this.getEventData();

    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      form: ""
    };
  },

  methods: {
    getEventData: function() {
      let apiURL = "http://localhost:1337/event/";
      let getID = this.$route.params.eventID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.form = response.data.data[0]), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },

    backButton: function() {
      this.$router.push("/event");
    },
    print: function() {
      const doc = new jsPDF({
        orientation: "p",
        unit: "mm",
        format: "a4",
        putOnlyUsedFonts: true
      });
      /** WITH CSS */
      var canvasElement = document.createElement("canvas");
      html2canvas(this.$refs.content, { canvas: canvasElement }).then(function(
        canvas
      ) {
        const img = canvas.toDataURL("image/png");
        doc.addImage(img, "JPEG", 1, 1);
        doc.save("QRcode_Event" + ".pdf");
      });
    }
  }
};
</script>
<style scoped>
</style>
