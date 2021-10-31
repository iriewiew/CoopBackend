<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>เพิ่มข้อมูลผู้ใช้งาน</h1>
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
              v-model="form.name"
              label="ชื่อผู้ใช้"
              v-validate="'required|max:255'"
              :error-messages="errors.collect('name')"
              data-vv-name="name"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 >
            <v-select
              item-text="title"
              item-value="title"
              v-model="form.status"
              :items="status"
              label="สถานะ"
              v-validate="'required'"
              :error-messages="errors.collect('status')"
              data-vv-name="status"
              required
            />
          </v-flex>
          <v-flex xs12>
            <v-text-field
              v-model="form.username"
              label="ชื่อบัญชีผู้ใช้"
              v-validate="'required|max:255'"
              :error-messages="errors.collect('user_name')"
              data-vv-name="user_name"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12>
            <v-text-field
              v-model="form.password"
              label="รหัสผ่าน"
              type="password"
              v-validate="'required|min:8'"
              :error-messages="errors.collect('password')"
              data-vv-name="password"
              required
              ref="password"
            ></v-text-field>

            <v-text-field
              v-model="form.confirmPassword"
              label="ยืนยันรหัสผ่าน"
              type="password"
              v-validate="'required|confirmed:password'"
              :error-messages="errors.collect('password_chk')"
              data-vv-name="password_chk"
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
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      status: [
        {
          title: "root"
        },
        {
          title: "admin"
        }
      ],
      form: {
        name: "",
        status: "",
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
          status: {
            required: "โปรดเลือกสถานะ"
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
  },
  methods: {
    saveData: function() {
      let apiURL = "http://localhost:1337/api/users/register";
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
            this.$router.push("/usermanage");
            console.log(response);
          });
        })
        .catch(err => {
          if (err.response.status == 500) {
            Swal.fire({
              type: "warning",
              title: "มีผู้ใช้งานนี้อยู่ในระบบแล้ว",
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
      this.$router.push("/usermanage");
    }
  }
};
</script>
<style scoped>
</style>
