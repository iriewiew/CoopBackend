<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>แก้ไขแบบประเมินย่อยของ ตอนที่ {{ data.eva_number }} {{ data.eva_name }}</h1>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-btn flat icon @click="submit('form')">
          <v-icon>save</v-icon>
        </v-btn>
      </v-toolbar-items>
    </v-toolbar>

    <v-card-title>
      <h1></h1>
      <v-spacer></v-spacer>
    </v-card-title>
    <v-container>
      <form>
        <v-flex xs12>
          <v-text-field
            v-model="form.eva_list_name"
            label="ชื่อแบบประเมินย่อย"
            v-validate="'required|'"
            :error-messages="errors.collect('eva_list_name')"
            data-vv-name="eva_list_name"
            required
          ></v-text-field>
        </v-flex>

        <!-- <v-btn @click="clear">clear</v-btn> -->
      </form>
    </v-container>
  </v-app>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      data: "",
      form: {
        eva_list_name: "",
        eva_id: this.$route.params.evaluationID
      },
      dictionary: {
        custom: {
          eva_list_name: {
            required: () => "โปรดกรอกข้อมูล",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          }
        }
      }
    };
  },
  computed: {},
  mounted() {
    this.getEvaluationData();
    this.getListData();
    this.$validator.localize("th", this.dictionary);
  },
  methods: {
    getEvaluationData: function() {
      let apiURL = "http://localhost:1337/evaluation/";
      let getID = this.$route.params.evaluationID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.data = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getListData: function() {
      let apiURL = "http://localhost:1337/evaluation/listdata/";
      let getID = this.$route.params.evaListID;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.form = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    saveData: function() {
      let apiURL = "http://localhost:1337/evaluation/list/update";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.form, setting)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "บันทึกข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.backButton();
            console.log(response);
          });
        })
        .catch(err => {
          Swal.fire({
            type: "error",
            title: "เกิดข้อผิดพลาด",
            showConfirmButton: false,
            timer: 1500
          });
          console.log(err);
        });
    },
    submit(form) {
      this.$validator.validateAll().then(result => {
        if (result) {
          // eslint-disable-next-line
          this.saveData();
          return;
        }
      });
    },
    backButton: function() {
      this.$router.push({
        name: "evaluationList",
        params: { evaluationID: this.$route.params.evaluationID }
      });
    }
  }
};
</script>
<style scoped>
</style>
