<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>แก้ไขบัญชีผู้ใช้งาน</h1>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-spacer></v-spacer>
    </v-toolbar>
    <div>
      <v-container>
        <br>
        <v-layout row>
          <v-flex xs2>
            <p style="text-align: left;">ชื่อ :</p>
          </v-flex>
          <v-flex xs12>
            <p v-show="nameShow" style="text-align: left;">{{ruleForm.name}}</p>
          </v-flex>
          <v-flex xs2>
            <v-btn color="primary" round flat small dark @click="nameShow = !nameShow">
              <span v-show="nameShow">แก้ไขชื่อผู้ใช้</span>
              <span v-show="!nameShow">ยกเลิก</span>
            </v-btn>
          </v-flex>
        </v-layout>
        <v-divider light></v-divider>

        <v-layout row>
          <v-flex xs12 v-if="!nameShow">
            <br>
            <v-text-field
              v-if="!nameShow"
              autofocus
              v-model="ruleForm.name"
              label="ชื่อผู้ใช้"
              data-vv-name="name"
              required
            ></v-text-field>
          </v-flex>
        </v-layout>

        <v-layout justify-end row v-show="!nameShow">
          <br>
          <v-btn
            v-if="ruleForm.name== '' && ruleForm.name.length <=0 "
            color="primary"
            round
            outline
            dark
            disabled
          >
            <span>บันทึก</span>
          </v-btn>
          <v-btn v-else color="primary" round outline dark @click="saveNameData('ruleForm')">
            <span>บันทึก</span>
          </v-btn>
        </v-layout>

        <br>

        <v-layout row>
          <v-flex xs2>
            <p style="text-align: left;">บัญชีผู้ใช้ :</p>
          </v-flex>
          <v-flex xs12>
            <p v-show="userSetting" style="text-align: left;">{{ruleForm.username}}</p>
          </v-flex>
          <v-flex xs2>
            <v-btn color="primary" round small flat dark @click="userSetting = !userSetting">
              <span v-show="userSetting">แก้ไขบัญชีผู้ใช้</span>
              <span v-show="!userSetting">ยกเลิก</span>
            </v-btn>
          </v-flex>
        </v-layout>
        <v-divider light></v-divider>

        <v-layout row wrap v-show="!userSetting">
          <v-flex xs12>
            <br>
            <v-text-field
              autofocus
              v-model="ruleForm.username"
              label="ชื่อบัญชีผู้ใช้"
              v-validate="'required|max:255'"
              :error-messages="errors.collect('user_name')"
              data-vv-name="user_name"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12>
            <v-text-field
              v-model="ruleForm.password"
              label="รหัสผ่านใหม่"
              type="password"
              v-validate="'required|min:8'"
              :error-messages="errors.collect('password')"
              data-vv-name="password"
              required
              ref="password"
            ></v-text-field>

            <v-text-field
              v-model="ruleForm.confirmPassword"
              label="ยืนยันรหัสผ่าน"
              type="password"
              v-validate="'required|confirmed:password'"
              :error-messages="errors.collect('password_chk')"
              data-vv-name="password_chk"
              required
            ></v-text-field>
          </v-flex>
        </v-layout>

        <div class="text-xs-right" v-show="!userSetting">
          <br>
          <v-btn color="primary" round outline dark @click="submitForm('ruleForm2')">
            <span>บันทึก</span>
          </v-btn>
        </div>
      </v-container>
    </div>
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  mounted() {
    this.getUserData();
    this.getData();
    this.$validator.localize("th", this.dictionary);
  },
  methods: {
    getData: function() {
      axios
        .get("http://localhost:1337/api/users/datatable", {
          headers: { Authorization: `${localStorage.tokenkey}` }
        })
        .then(
          response => (
            (this.desserts = response.data.data),
            (this.loading = false),
            (this.nodata = "ไม่มีข้อมูล")
          )
        )
        .catch(err => {
          if (err.response.status == 401) {
            Swal.fire({
              type: "warning",
              title: "เซสชั่นหมดอายุแล้ว",
              text: "กรุณาเข้าสู่ระบบใหม่",
              showConfirmButton: true
            });
            localStorage.clear();
            this.$store.state.getApiData.tokenkey = null;
          }
          if (err.response.status == 404) {
            Swal.fire({
              type: "warning",
              title: "เกิดข้อผิดพลาด",
              showConfirmButton: true
            });
          }
        });
    },
    addButton: function() {
      //   this.$router.push("/position/add");
    },
    getUserData: function() {
      let apiURL = "http://localhost:1337/api/users/";
      let getID = this.$route.params.userID;
      let view = "/view";
      axios
        .get(apiURL + getID + view, {
          headers: { Authorization: `${localStorage.tokenkey}` }
        })
        .then(response => (this.ruleForm = response.data.data));
    },

    submitNameForm(ruleForm) {
      this.$validator.validateAll().then(result => {
        if (result) {
          Swal.fire({
            title: "ยืนยันการบันทึกข้อมูล?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#67C23A",

            cancelButtonText: "ยกเลิก",
            confirmButtonText: "บันทึก"
          }).then(result => {
            if (result.value) {
          Swal.fire({
            title: "บันทึกข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            // this.getData();
          });
            }
          });
          return;
        }
      });
    },

    saveNameData: function() {
      let apiURL = "http://localhost:1337/api/users/updatename";
      this.axios
        .post(apiURL, this.ruleForm, {
          headers: { Authorization: `${localStorage.tokenkey}` }
        })
        .then(response => {
          Swal.fire({
            title: "แก้ไขข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            this.getUserData();
            this.nameShow = true;
            localStorage.name = this.ruleForm.name;
            this.$store.state.getApiData.name = this.ruleForm.name;
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
    submitForm(ruleForm2) {
      this.$validator.validateAll().then(result => {
        if (result) {
          Swal.fire({
            title: "ยืนยันการบันทึกข้อมูล?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#67C23A",

            cancelButtonText: "ยกเลิก",
            confirmButtonText: "บันทึก"
          }).then(result => {
            if (result.value) {
              this.saveData();
            }
          });
          return;
        }
      });
    },
    saveData: function() {
      let apiURL = "http://localhost:1337/api/users/update";
      this.axios
        .post(apiURL, this.ruleForm, {
          headers: { Authorization: `${localStorage.tokenkey}` }
        })
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "แก้ไข ชื่อผู้ใช้กับรหัสผ่าน เรียบร้อย",
            type: "success",
            showConfirmButton: true
          }).then(result => {
            console.log("ok3");
          });
        })
        .catch(err => {
          if (err.response.status == 400) {
            Swal.fire({
              type: "warning",
              title: "ชื่อบัญชีผู้ใช้ชื่อนี้ถูกใช้งานแล้ว",
              text: "โปรดตรวจสอบและลองใหม่อีกครั้ง",
              showConfirmButton: true
            });
          } else {
            Swal.fire({
              type: "error",
              title: "เกิดข้อผิดพลาด",
              showConfirmButton: false,
              timer: 1500
            });
          }
          console.log(err);
        });
    },
    backButton: function() {
      this.$router.push("/usermanage");
    }
  },

  data() {
    return {
      RuleData: [{ name: "Admin" }, { name: "Human resources" }],

      selectedData: { id: "", username: "", name: "" },
      desserts: [],
      nameShow: true,
      userSetting: true,

      ruleForm: {
        id: "",
        username: "",
        password: "",
        confirmPassword: ""
      },
      dictionary: {
        custom: {
          name: {
            required: () => "โปรดกรอกชื่อผู้ใช้",
            alpha: "ไม่ควรใช้อักขระพิเศษ"

            // custom messages
          },
          user_name: {
            required: () => "โปรดกรอกบัญชีชื่อผู้ใช้",
            alpha: "ไม่ควรใช้อักขระพิเศษ"

            // custom messages
          },
          password: {
            required: () => "โปรดกรอกรหัสผ่านใหม่",
            min: "รหัสผ่านควรมี 8 ตัวขึ้นไป"
            // custom messages
          },
          password_chk: {
            required: () => "โปรดยืนยันรหัสผ่าน",
            confirmed: "รหัสผ่านไม่ตรงกัน"
          }
        }
      }
    };
  }
};
</script>

<style scoped>
</style>

