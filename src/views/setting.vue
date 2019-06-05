<template>
  <v-app>
    <v-card-title>
      <h1>การตั้งค่า</h1>
      <v-spacer></v-spacer>
      <!-- <v-text-field
        v-show="!searchShow"
        v-model="search"
        append-icon="close"
        label="ค้นหา"
        single-line
        hide-details
        @click:append="searchShow = !searchShow,search= null"
      ></v-text-field>

      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
           <v-btn icon v-on="on" v-show="searchShow" @click="searchShow = !searchShow" color="gray">
        <v-icon>search</v-icon>
      </v-btn>

        </template>
        <span>ค้นหาข้อมูล</span>
      </v-tooltip>




      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon color="gray" v-on="on" @click="addButton">
            <v-icon>add</v-icon>
          </v-btn>
        </template>
        <span>เพิ่มข้อมูล</span>
      </v-tooltip>-->
    </v-card-title>
  </v-app>
</template>
