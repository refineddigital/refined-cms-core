<template>
    <div>
      <label :for="'form--'+field.name" class="form__label">{{ field.label }}</label>
      <input type="date" class="form__control" :name="field.name" required :id="'form--'+field.name" autocomplete="off">
    </div>
</template>

<script>
  import Flatpickr from 'flatpickr';
  import moment from 'moment';
  import 'flatpickr/dist/themes/material_blue.css';

  export default {

    props: [ 'field', 'value' ],

    data() {
      return {
        datePicker: null,
        dateFormat: 'YYYY-MM-DD',
        config: {
          altInput: true,
          altFormat: 'F j, Y',
          dateFormat: 'Y-m-d',
          enableTime: false,
          defaultDate: ''
        }
      }
    },

    mounted() {
      this.config.defaultDate = this.value ? moment(this.value, this.dateFormat).format(this.dateFormat) : moment().format(this.dateFormat);
      this.datePicker = new Flatpickr('#form--'+this.field.name, this.config);
    }

  }
</script>
