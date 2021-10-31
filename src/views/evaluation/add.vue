<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>เพิ่มข้อมูลแบบประเมินใหม่</h1>
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
          <!-- <v-flex xs12>
            <v-text-field
              v-model="form.eva_number"
              label="รหัสแบบประเมิน"
              v-validate="'required|numeric'"
              :error-messages="errors.collect('eva_number')"
              data-vv-name="eva_number"
              required
              disabled
            ></v-text-field>
          </v-flex> -->
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

        </v-layout>
      </v-container>
      <!-- <div v-for=" data in form.eva_data">

      </div>-->

    </v-form>
  </v-app>
</template>
<script>
export default {
  mounted() {
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      form: {
        eva_name: "",
        eva_number: new Date().valueOf(),
        eva_detail: "",
        eva_created_name: this.$store.state.getApiData.name,
        
        // eva_data: {

        //       name:"w",
        //       titile:"w",
        //       eva_number: new Date().valueOf(),
        //       eva_list:
        //       {

        //       }

        // }
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
    saveData: function() {
      let apiURL = "http://localhost:1337/evaluation/create";
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
          if (err.response.status == 500) {
            Swal.fire({
              type: "warning",
              title: "มีข้อมูลนี้อยู่ในระบบแล้ว",
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
      this.$router.push("/evaluation");
    }
  }
};
</script>
<style scoped>
</style>
