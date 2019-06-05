<template>
  <div>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>ผลการประเมินของ "{{eventData.event_name}}"</h1>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-spacer></v-spacer>

      <v-toolbar-items>

      </v-toolbar-items>
    </v-toolbar>
    <!-- {{data}} -->
    
    <v-container>
{{data}}
 </v-container>

  </div>
</template>


<script>
import axios from "axios";
export default {
  mounted() {
    this.getData();
    this.getEventData();

  },
  data() {
    return {
      data: "",
      eventData: "",
            headers: [
        {
          text: 'ข้อความ',
          align: 'left',
          sortable: false,
          value: 'name'
        },
   
      ],
      desserts: [
      ]
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
            (this.eventData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getData: function() {
      let apiURL = "http://localhost/coopApi/?id=";
      let getID = this.$route.params.eventID;
      let eva = "&eva_id=";
      let evaID = this.$route.params.evaID;
  
      axios
        .get(apiURL + getID + eva + evaID)
        .then(
          response => (
            (this.data = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },

    backButton: function() {
      this.$router.push("/evaluation_report");
    }
  }
};
</script>