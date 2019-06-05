<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>แก้ไขข้อมูลแบบประเมิน</h1>
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
    <v-form>
      <v-container>
        <v-layout row wrap>
          <v-flex xs12>
            <v-text-field
              v-model="form.eva_number"
              label="รหัสแบบประเมิน"
              v-validate="'required|numeric'"
              :error-messages="errors.collect('eva_number')"
              data-vv-name="eva_number"
              required
              disabled
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.eva_name"
              label="ชื่อแบบประเมิน"
              v-validate="'required|max:255'"
              :counter="255"
              :error-messages="errors.collect('eva_name')"
              data-vv-name="eva_name"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12>
            <v-textarea
              v-model="form.eva_detail"
              label="รายละเอียด"
              value="eva_detail"
              v-validate="'required|'"
              :error-messages="errors.collect('eva_detail')"
              data-vv-name="eva_detail"
              required
            ></v-textarea>
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.eva_achieve_point"
              label="ผลบรรลุการเข้าร่วม (ร้อยละ)"
              v-validate="'required|max:3|numeric'"
              :counter="3"
              :error-messages="errors.collect('eva_achieve_point')"
              data-vv-name="eva_achieve_point"
              required
            ></v-text-field>
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
  </v-app>
</template>
<script>
import axios from "axios";
export default {
  mounted() {
    this.getEvaluationData();
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      form: {
        eva_name: "",
        eva_detail: "",
        eva_achieve_point: ""
      },
      dictionary: {
        custom: {
          eva_name: {
            required: () => "โปรดกรอกชื่อแบบประเมิน",
            max: "จำนวนอักขระไม่ควรเกิน 255 ตัวอักษร",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          },
          eva_detail: {
            required: () => "โปรดกรอกรายละเอียดของแบบประเมิน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            numeric: "ตอนของแบบประเมินควรเป็นตัวเลขเท่านั้น"

            // custom messages
          },
          eva_achieve_point: {
            required: () => "โปรดกรอกคะแนน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            numeric: "ตัวเลขเท่านั้น",
            max: "คะแนนไม่ควรเกิน 3 หลัก"
            // custom messages
          }
        }
      }
    };
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
            (this.form = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    saveData: function() {
      let apiURL = "http://localhost:1337/evaluation/update";
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
            this.$router.push("/evaluation");
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
      this.$router.push("/evaluation");
    }
  }
};
</script>
<style scoped>
</style>
