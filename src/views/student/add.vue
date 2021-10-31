<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>เพิ่มข้อมูลนักศึกษา</h1>
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
          <v-flex xs12 sm12 md12>
            <v-text-field
              v-model="form.user_id"
              label="รหัสนักศึกษา"
              v-validate="'required|max:11|numeric|min:11'"
              :counter="11"
              :error-messages="errors.collect('user_id')"
              data-vv-name="user_id"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md2>
            <v-select
              item-text="title"
              item-value="title"
              v-model="form.prefix_name"
              :items="prefix_name"
              label="คำนำหน้า"
              v-validate="'required'"
              :error-messages="errors.collect('selectPrefix')"
              data-vv-name="selectPrefix"
              required
            />
          </v-flex>
          <v-flex xs12 md5>
            <v-text-field
              v-model="form.first_name"
              label="ชื่อ"
              v-validate="{required:true,regex:/^[A-Za-z0-9ก-๙]+$/}"
              :error-messages="errors.collect('first_name')"
              data-vv-name="first_name"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 md5>
            <v-text-field
              v-model="form.last_name"
              label="นามสกุล"
              v-validate="{required:true,regex:/^[A-Za-z0-9ก-๙]+$/}"
              :error-messages="errors.collect('last_name')"
              data-vv-name="last_name"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12 md6>
            <v-select
              item-text="faculty_name"
              item-value="id"
              v-model="form.faculty_name"
              :items="facultyData"
              label="คณะ"
              v-validate="'required'"
              :error-messages="errors.collect('selectfaculty')"
              data-vv-name="selectfaculty"
              required
            />
          </v-flex>
          <v-flex xs12 md6 @click="getMajorData();">
            <v-select
              no-data-text="ไม่พบข้อมูล"
              item-text="major_name"
              item-value="id"
              v-model="form.major_name"
              :items="majorData"
              label="สาขาวิชา"
              v-validate="'required'"
              :error-messages="errors.collect('selectMajor')"
              data-vv-name="selectMajor"
              required
            />
          </v-flex>
          <v-flex xs12>
            <v-select
              v-model="form.std_year"
              :items="years"
              label="ปีการศึกษา"
              v-validate="'required'"
              :error-messages="errors.collect('std_year')"
              data-vv-name="std_year"
              required
            />
          </v-flex>
          <!-- <v-flex xs12 md12>
            <v-text-field
              v-model="form.email"
              label="อีเมล"
              v-validate="'required|email'"
              :error-messages="errors.collect('email')"
              data-vv-name="email"
              required
              type="email"
            ></v-text-field>
          </v-flex>-->
          <!-- <v-btn @click="clear">clear</v-btn> -->
        </v-layout>
        <br />
        <br />**ระบบจะตั้งรหัสผ่านของผู้ใช้เริ่มต้นเป็น "1111111" (เลข 1 จำนวน 8 ตัวอักษร)
      </v-container>
    </v-form>
  </v-app>
</template>
<script>
import axios from "axios";

export default {
  mounted() {
    this.getData();
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      facultyData: "",
      majorData: [{ id: 0, major_name: "ไม่พบข้อมูล" }],
      prefix_name: [
        {
          title: "นาย"
        },
        {
          title: "นาง"
        },
        {
          title: "นางสาว"
        }
      ],
      form: {
        user_id: "",
        prefix_name: "",
        first_name: "",
        last_name: "",
        role_name: "student",
        major_name: "",
        faculty_name: "",
        email: "",
        std_year: ""
      },
      dictionary: {
        attributes: {
          email: "E-mail Address"
          // custom attributes
        },
        custom: {
          user_id: {
            required: () => "โปรดกรอกรหัสนักศึกษา",
            min: "รหัสนักศึกษาควรเป็นตัวเลขจำนวน 11 หลัก",
            max: "รหัสนักศึกษาควรเป็นตัวเลขไม่เกิน 11 หลัก",
            numeric: "รหัสนักศึกษาควรเป็นตัวเลขเท่านั้น"
            // custom messages
          },
          first_name: {
            required: () => "โปรดกรอกชื่อ",
            regex: "ไม่ควรใช้อักขระพิเศษ"

            // custom messages
          },
          last_name: {
            required: () => "โปรดกรอกนามสกุล",
            regex: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          },
          selectPrefix: {
            required: "โปรดเลือกคำนำหน้า"
          },
          selectfaculty: {
            required: "โปรดเลือกคณะ"
          },
          selectMajor: {
            required: "โปรดเลือกสาขาวิชา"
          },
          email: {
            required: () => "โปรดกรอกอีเมล",
            email: "รูปแบบอีเมลไม่ถูกต้อง"
          },
          std_year: {
            required: "โปรดเลือกปีการศึกษา"
          }
        }
      }
    };
  },
  methods: {
    saveData: function() {
      let apiURL = "http://localhost:1337/member/create";
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
            this.$router.push("/student");
            console.log(response);
          });
        })
        .catch(err => {
          if (err.response.status == 500) {
            Swal.fire({
              type: "warning",
              title: "มีรหัสนักศึกษานี้อยู่ในระบบแล้ว",
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
    getData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/faculty/datatable", setting)
        .then(
          response => (
            (this.facultyData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    getMajorData: function() {
      let apiURL = "http://localhost:1337/faculty/major/";
      let getID = this.form.faculty_name;
      let view = "/view";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get(apiURL + getID + view, setting)
        .then(
          response => (
            (this.majorData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
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
      this.$router.push("/student");
    }
  },
  computed: {
    years() {
      const year = new Date().getFullYear() + 543;
      return Array.from(
        { length: year - 2558 },
        (value, index) => 2559 + index
      );
    }
  }
};
</script>
<style scoped>
</style>
