<template>
  <v-app>
            <v-btn flat @click="dialog = true">
        <v-icon>add</v-icon>เพิ่มด้านของแบบประเมิน
      </v-btn>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="800px">
        <v-card>
          <v-card-title>
            <span class="headline">เพิ่มด้านของแบบประเมิน</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field
                    label="ชื่อด้าน"
                    v-model="form.eva_titile_name"
                    v-validate="'required|max:255'"
                    :counter="255"
                    :error-messages="errors.collect('titile_name')"
                    data-vv-name="titile_name"
                    required
                  ></v-text-field>
                  <v-text-field
                    label="ผลบรรลุ (ร้อยละ)"
                    v-model="form.eva_titile_achieve_point"
                    v-validate="'required|max:3|numeric'"
                    :counter="3"
                    :error-messages="errors.collect('eva_titile_achieve_point')"
                    data-vv-name="eva_titile_achieve_point"
                    required
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" flat @click="dialog = false,$validator.reset();">ยกเลิก</v-btn>
            <v-btn flat icon @click="submit('form')">
              <v-icon>save</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </v-app>
</template>


<script>
export default {
  mounted() {
    this.$validator.localize("th", this.dictionary);
  },
  data() {
    return {
      dialog: false,
      form: {
        eva_titile_name: "",
        eva_titile_achieve_point: "",
        eva_id: this.$route.params.evaluationID
      },
      dictionary: {
        custom: {
          titile_name: {
            required: () => "โปรดกรอกข้อมูลให้ครบถ้วน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            max: "จำนวนอักขระไม่ควรเกิน 255 ตัวอักษร"

            // custom messages
          },
          list_name: {
            required: () => "โปรดกรอกข้อมูลให้ครบถ้วน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            max: "จำนวนอักขระไม่ควรเกิน 255 ตัวอักษร"

            // custom messages
          },
          eva_point: {
            required: () => "โปรดกรอกคะแนน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            numeric: "ตัวเลขเท่านั้น",
            max: "คะแนนไม่ควรเกิน 3 หลัก"
            // custom messages
          },
          eva_titile_achieve_point: {
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
      let apiURL = "http://localhost:1337/evaluation/titile/create";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.form, setting)
        .then(response => {
          this.dialog = false;

          console.log("ok2");
          Swal.fire({
            title: "เพิ่มเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.form.eva_titile_name = "";
            this.form.eva_titile_achieve_point = "";

            this.getData();
            this.getTitileData();
            this.getListData();
            this.$validator.reset();
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
          this.dialog = false;
          this.$validator.reset();
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

  },

};
</script>
