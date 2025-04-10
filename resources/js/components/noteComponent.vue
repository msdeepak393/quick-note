<template>
  <div class="container-fluid">
    <div class="row">
      <template v-if="allNotes == null || allNotes == ''">
        <h4 class="m-5">Hi Welcome to CodeSolving.. Please Add Your Note</h4>
      </template>
      <template v-else>
        <div class="col-md-4 p-0" v-for="noteRow in allNotes" :key="noteRow.id">
          <div class="card shadow border-primary m-1">
            <div class="card-header">
              <div class="row">
                <div class="col-10">
                  <input
                    type="text"
                    class="form-control border-0"
                    :value="noteRow.title"
                    @keyup="updateValue($event, noteRow.id, 'title')"
                    placeholder="title"
                  />
                </div>
                <div class="col-2 pt-1">
                  <button
                    class="btn btn-danger btn-sm float-right"
                    @click="deleteNote(noteRow.id)"
                  >
                    Delete
                  </button>
                </div>
                <div class="col-12">
                  <small class="text-danger"
                    >Created: {{ noteRow.created_at | moment }}</small
                  >
                </div>
              </div>
            </div>
            <div class="card-body">
              <textarea
                class="form-control border-0"
                placeholder="Your Note"
                rows="3"
                @keyup="updateValue($event, noteRow.id, 'note')"
                >{{ noteRow.note }}</textarea
              >
            </div>
          </div>
        </div>
      </template>
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Your Note</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label>Title</label>
                <input
                  type="text"
                  v-model="formData.title"
                  class="form-control"
                  placeholder="title"
                />
              </div>
              <div class="form-group">
                <label>Your Note</label>
                <textarea
                  v-model="formData.note"
                  class="form-control"
                  placeholder="Your Note"
                  rows="3"
                ></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              ref="modalClose"
            >
              Close
            </button>
            <button
              type="button"
              @click="submitFunction"
              class="btn btn-primary"
            >
              Save changes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
export default {
  data() {
    return {
      formData: {
        title: null,
        note: null,
      },
      allNotes: null,
    };
  },
  mounted() {
    this.getAllData();
    console.log("Component mounted.");
  },
  methods: {
    getAllData() {
      Swal.fire({
        title: "Loading",
        didOpen: () => {
          Swal.showLoading();
        },
      });
      axios
        .get("/all-data")
        .then((response) => {
          var data = response.data;
          this.allNotes = data.notes;
          Swal.close();
        })
        .catch((error) => console.log(error));
    },
    submitFunction() {
      this.$refs.modalClose.click(); // to close modal
      Swal.fire({
        title: "Loading",
        didOpen: () => {
          Swal.showLoading();
        },
      });
      if (this.formData.title == null && this.formData.note == null) {
        Swal.close();
        Swal.fire({
          position: "center",
          icon: "warning",
          title: "Please fill the note",
          showConfirmButton: false,
          timer: 1500,
        });
      } else {
        axios
          .post("/add-note", this.formData)
          .then((response) => {
            var data = response.data;
            this.getAllData();
            if (data.success) {
              Swal.close();
              this.formData.title = null;
              this.formData.note = null;
            } else {
              Swal.close();
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "An error occured please try again",
                showConfirmButton: false,
                timer: 1000,
              });
            }
          })
          .catch((error) => console.log(error));
      }
    },
    updateValue(event, id, field) {
      axios
        .post("/update-data", {
          value: event.target.value,
          id: id,
          field: field,
        })
        .then((response) => {
          var data = response.data;
          if (data.success) {
            // alert("updated successfully");
          } else {
            // alert("an error occured");
          }
        })
        .catch((error) => console.log(error));
    },
    deleteNote(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("/delete-data", { id: id })
            .then((response) => {
              var data = response.data;
              this.getAllData();
              if (data.success) {
                Swal.fire({
                  position: "center",
                  icon: "success",
                  title: "Note Updated",
                  showConfirmButton: false,
                  timer: 1000,
                });
              } else {
                Swal.fire({
                  position: "center",
                  icon: "warning",
                  title: "An error occured please try again",
                  showConfirmButton: false,
                  timer: 1000,
                });
              }
            })
            .catch((error) => console.log(error));
        }
      });
    },
  },
  filters: {
    moment(date) {
      return moment(date).format("MMMM Do YYYY, h:mm:ss a");
    },
  },
};
</script>
