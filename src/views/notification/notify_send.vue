<template>
  <v-app>
    <v-toolbar flat>
      <v-toolbar-items>
        <v-btn flat icon @click="backButton();">
          <v-icon>arrow_back</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-card-title>
        <h1>ส่งการแจ้งเตือนใหม่</h1>
        <v-spacer></v-spacer>
      </v-card-title>
      <v-spacer></v-spacer>

      <v-toolbar-items>
        <v-btn
          flat
          icon
          @click="submit('form'),formSave.notification_msg = sendNotify.fcm.notification.body"
          :disabled="sendBtn"
        >
          <v-icon>send</v-icon>
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
          <v-flex xs12 @click="sendBtn=true">
            <v-text-field
              v-model="form.titile"
              label="ข้อความ"
              v-validate="'required|max:50'"
              :counter="50"
              :error-messages="errors.collect('titile')"
              data-vv-name="titile"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 md12 @click="sendBtn=true">
            <v-select
              item-text="event_name"
              item-value="event_name"
              v-model="form.event"
              :items="eventData"
              label="เลือกกิจกรรม"
              v-validate="'required'"
              :error-messages="errors.collect('event')"
              data-vv-name="event"
              required
            />
          </v-flex>
          <v-flex xs12 @click="sendBtn=true">
            <v-text-field
              v-model="form.end"
              label="ข้อความ"
              v-validate="'required|max:50'"
              :counter="50"
              :error-messages="errors.collect('titile')"
              data-vv-name="titile"
              required
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm12 text-xs-center>
            <div>
              <v-btn
                outline
                round
                @click="sendNotify.fcm.notification.body = form.titile + ' '+ form.event  + ' '+form.end,sendBtn=false"
              >
                <v-icon left>visibility</v-icon>
                <span>ดูตัวอย่างข้อความการแจ้งเตือน</span>
              </v-btn>
            </div>
          </v-flex>
        </v-layout>
      </v-container>
    </v-form>
    <br>
<v-container>
    <v-layout row>
      <v-flex xs12>
        <v-card>
          <v-toolbar color="pink" dark>
            <v-toolbar-side-icon></v-toolbar-side-icon>
            <v-toolbar-title>การแจ้งเตือน</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-list two-line>
            <template>
              <v-list-tile :key="index" avatar ripple @click>
                <v-list-tile-content>
                  <v-list-tile-title>CoopApp</v-list-tile-title>
                  <v-list-tile-sub-title class="text--primary">
                    <b>{{sendNotify.fcm.notification.title}}</b>
                  </v-list-tile-sub-title>
                  <v-list-tile-sub-title>{{sendNotify.fcm.notification.body}}</v-list-tile-sub-title>
                </v-list-tile-content>
                <v-list-tile-action>
                  <v-list-tile-action-text>ตอนนี้</v-list-tile-action-text>
                </v-list-tile-action>
              </v-list-tile>
            </template>
          </v-list>
        </v-card>
      </v-flex>
    </v-layout>
</v-container>
  </v-app>
</template>
<script>
import axios from "axios";
export default {
  mounted() {
    this.$validator.localize("th", this.dictionary);
    this.getEventData();
  },
  data() {
    return {
      sendBtn: true,
      list: {
        name: ""
      },
      eventData: "",
      form: {
        titile: "",
        event: "",
        end: ""
      },
      formSave: {
        notification_msg: ""
      },
      sendNotify: {
        interests: ["hello"],
        fcm: {
          notification: {
            title: "ศูนย์สหกิจศึกษา",
            body: ""
          }
        }
      },
      dictionary: {
        custom: {
          event: {
            required: () => "โปรดเลือกกิจกรรม",
            alpha: "ไม่ควรใช้อักขระพิเศษ"
            // custom messages
          },
          titile: {
            required: () => "โปรดกรอกข้อความการแจ้งเตือน",
            alpha: "ไม่ควรใช้อักขระพิเศษ",
            max: "ข้อความไม่ควรเกิน 50 ตัวอักษร"

            // custom messages
          }
        }
      }
    };
  },
  methods: {
    dialogSend: function() {
      Swal.fire({
        title: "ยืนยันการส่งการแจ้งเตือน",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#42A5F5",

        cancelButtonText: "ยกเลิก",
        confirmButtonText: "ยืนยัน"
      }).then(result => {
        if (result.value) {
          this.notifiSend();
        }
      });
    },
    getEventData: function() {
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      axios
        .get("http://localhost:1337/event/datatable", setting)
        .then(
          response => (
            (this.eventData = response.data.data), (this.isLoading = false)
          )
        )
        .catch(err => {
          console.log(err.response);
        });
    },
    notifiSend: function() {
      let apiURL = "https://2542e143-54b2-497e-b818-91fbffc77642.pushnotifications.pusher.com/publish_api/v1/instances/2542e143-54b2-497e-b818-91fbffc77642/publishes";
      let setting = {
        headers: { Authorization: `Bearer 3D7D8EAFD50BA93F5060BF312202283DC82468E13353B1C92E8394D4BA8048DC` }
      };
      this.axios
        .post(apiURL, this.sendNotify, setting)
        .then(response => {
          this.saveData();
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
    saveData: function() {
      let apiURL = "http://localhost:1337/notification/create";
      let setting = {
        headers: { Authorization: `${localStorage.tokenkey}` }
      };
      this.axios
        .post(apiURL, this.formSave, setting)
        .then(response => {
          console.log("ok2");
          Swal.fire({
            title: "ส่งข้อมูลเรียบร้อย",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          }).then(result => {
            console.log("ok3");
            this.$router.push("/notification");
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
          this.dialogSend();
          return;
        }
      });
    },
    backButton: function() {
      this.$router.push("/notification");
    }
  }
};
</script>
<style scoped>
</style>
