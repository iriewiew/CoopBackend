<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>เพิ่มข้อมูลสาขาวิชา ในคณะ {{ data.faculty_name }}</h1>
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
            v-model="form.major_name"
            label="ชื่อสาขา"
            v-validate="'required|'"
            :error-messages="errors.collect('major_name')"
            data-vv-name="major_name"
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
        major_name: "",
        faculty_id: this.$route.params.facultyID
      },
      dictionary: {
        custom: {
          major_name: {
            required: () => "โปรดกรอกชื่อสาขาวิชา",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          }
        }
      }
    };
  },
  computed: {},
  mounted() {
    this.getFacultyData();
    this.$validator.localize("th", this.dictionary);
  },
  methods: {
    getFacultyData: function() {
      let apiURL = "http://localhost:1337/faculty/";
      let getID = this.$route.params.facultyID;
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
    saveData: function() {
      let apiURL = "http://localhost:1337/major/create";
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
        name: "facultyMajor",
        params: { facultyID: this.$route.params.facultyID }
      });
    }
  }
};
</script>
<style scoped>
</style>
