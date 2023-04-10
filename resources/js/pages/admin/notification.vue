<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Notification</h5>
                                <p class="mb-0 text-sm">View sent notifications and send new ones</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="notification_modal.show()">
                                        Send Notification
                                        <i class="fa-regular fa-comment-plus ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable-search" class="table table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>To</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Url</th>
                                <th>Schedule</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="notifyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Notification</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <label class="form-label mt-2">Send type</label>
                            <select
                                id="type"
                                class="form-control"
                                name="type"
                                v-model="notification.type"
                                :disabled="selected_id !== null"
                            >
                                <option value="email">Email</option>
                                <option value="push">Push notification</option>
                            </select>
                        </div>
                        <div class="col-md-8 col-12" v-if="notification.type === 'email'">
                            <label class="form-label mt-2">Email</label>
                            <input
                                id="audience"
                                name="audience"
                                class="form-control"
                                type="text"
                                placeholder="User email"
                                v-model="notification.to"
                                :disabled="selected_id !== null"
                            />
                        </div>
                        <div class="col-md-4 col-12" v-show="notification.type !== 'email'">
                            <label class="form-label mt-2">Audience</label>
                            <select
                                id="audience"
                                class="form-control"
                                name="audience"
                                v-model="notification.to"
                                :disabled="selected_id !== null"
                            >
                                <option value="Subscribed Users">Send to Subscribed Users</option>
                                <option value="Inactive Users">Send to Inactive Users</option>
                                <option value="Active Users">Send to Active Users</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12" v-show="notification.type !== 'email'">
                            <label class="form-label mt-2">Schedule ?</label>
                            <input
                                id="schedule"
                                name="schedule"
                                class="form-control"
                                type="datetime-local"
                                placeholder="Title"
                                v-model="notification.schedule"
                                :disabled="selected_id !== null"
                            />
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label mt-2">Title</label>
                            <input
                                id="title"
                                name="title"
                                class="form-control"
                                type="text"
                                placeholder="Title"
                                v-model="notification.title"
                                :disabled="selected_id !== null"
                            />
                        </div>
                        <div v-if="notification.type === 'email'" class="col-12 mt-3">
                            <label class="form-label mt-2">Message</label>
                            <ckeditor :editor="ckeditor.editor" v-model="notification.message"
                                      :config="ckeditor.editorConfig" :disabled="selected_id !== null"></ckeditor>
                        </div>
                        <div v-else class="col-12 mt-3">
                            <label class="form-label mt-2">Message</label>
                            <textarea
                                id="message"
                                name="message"
                                class="form-control"
                                rows="8"
                                placeholder="Message"
                                v-model="notification.message"
                                :disabled="selected_id !== null"
                            ></textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label mt-2">Launch URL</label>
                            <input
                                id="url"
                                name="url"
                                class="form-control"
                                type="text"
                                placeholder="Launch Url"
                                v-model="notification.url"
                                :disabled="selected_id !== null"
                            />
                        </div>
                        <!--                        <div class="col-12 mt-3">-->
                        <!--                            <label class="form-label mt-2">Image ( not work at this time )</label>-->
                        <!--                            <input-->
                        <!--                                id="launchUrl"-->
                        <!--                                name="launchUrl"-->
                        <!--                                class="form-control"-->
                        <!--                                type="text"-->
                        <!--                                placeholder="Launch Url"-->
                        <!--                                disabled-->
                        <!--                            />-->
                        <!--                            <img class="mt-3 img-fluid"-->
                        <!--                                 src="#"-->
                        <!--                                 alt="">-->
                        <!--                        </div>-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button v-if="!selected_id" type="button" class="btn btn-primary" @click="this.sendNotification">
                        Send
                    </button>
                    <button v-else type="button" class="btn btn-danger" @click="this.deleteNotification">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {DataTable} from "simple-datatables";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import CKEditor from '@ckeditor/ckeditor5-vue';
export default {
    name: "Notification",
    components: {
        ckeditor: CKEditor.component,
    },
    title() {
        return "System Setting";
    },
    data() {
        return {
            data: null,
            unsubscribe: null,
            table: null,
            notification: {
                type: 'email',
                to: '',
                title: '',
                message: '',
                url: '',
                schedule: '',
            },
            action_type: 'get',
            notification_modal: null,
            selected_id: null,
            selected_row: null,
            ckeditor: {
                editor: ClassicEditor,
                editorConfig: {}
            }
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminNotification/request') {
                if (this.action_type === 'get') {
                } else if (this.action_type === 'send') {
                    this.$root.showSnackbar('Sending notification...', 'info');
                } else if (this.action_type === 'delete') {
                    this.$root.showSnackbar('Deleting notification...', 'info');
                }
            } else if (mutation.type === 'adminNotification/success') {
                if (this.action_type === 'get') {
                    this.data = mutation.payload;
                    this.init();
                } else if (this.action_type === 'send') {
                    this.$root.showSnackbar('Notification sent successfully', 'success');
                    this.data.push(mutation.payload.data);
                    this.table.rows.add([
                        mutation.payload.data.id ?? 'Not set',
                        mutation.payload.data.to ?? 'Not set',
                        mutation.payload.data.title ?? 'Not set',
                        mutation.payload.data.message ? mutation.payload.data.message.substring(0, 50) + '...' : 'Not set',
                        mutation.payload.data.url ?? 'Not set',
                        mutation.payload.data.schedule ?? 'Not set',
                    ]);
                    this.notification_modal.hide();
                } else if (this.action_type === 'delete') {
                    this.$root.showSnackbar('Notification deleted successfully', 'success');
                    this.notification_modal.hide();
                    this.table.rows.remove(this.selected_row);
                    this.selected_row = null;
                }
                this.action_type = 'get';
            } else if (mutation.type === 'adminNotification/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
                this.action_type = 'get';
            }
        });
        this.$store.dispatch('adminNotification/getNotification');
    },
    beforeUnmount() {
        this.unsubscribe();
        this.table.destroy();
        this.notification_modal.hide();
        this.notification_modal.dispose();
    },
    methods: {
        init() {
            // init datatable
            this.table = new DataTable("#datatable-search", {
                searchable: true,
                fixedHeight: true,
            });
            this.table.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                let data = this.data[rowIndex];
                this.selected_row = rowIndex;
                this.notification = {
                    type: data.type,
                    to: data.to,
                    title: data.title,
                    message: data.message,
                    url: data.url,
                    schedule: data.schedule,
                };
                this.selected_id = data.id;
                this.notification_modal.show();
            });
            this.data.forEach((item) => {
                let data_array = [
                    item.id ? item.id : 'Not set',
                    item.to ? item.to : 'Not set',
                    item.title ? item.title : 'Not set',
                    item.message ? item.message : 'Not set',
                    item.url ? item.url : 'Not set',
                    item.schedule ? item.schedule : 'Not scheduled',
                ];
                // limit message length
                if (data_array[3].length > 50) {
                    data_array[3] = data_array[3].substring(0, 50) + '...';
                }
                this.table.rows.add(data_array);
            });
            const bootstrap = this.$store.state.config.bootstrap;
            // init modal
            this.notification_modal = new bootstrap.Modal(document.getElementById('notifyModal'), {
                show: true,
            });
            // set hide event for modal
            this.notification_modal._element.addEventListener('hide.bs.modal', () => {
                this.notification = {
                    type: 'email',
                    to: '',
                    title: '',
                    message: '',
                    url: '',
                    schedule: '',
                };
                this.selected_id = null;
            });
        },
        sendNotification() {
            this.action_type = 'send';
            this.$store.dispatch('adminNotification/sendNotification', this.notification);
        },
        deleteNotification() {
            if (this.selected_id) {
                this.action_type = 'delete';
                this.$store.dispatch('adminNotification/deleteNotification', this.selected_id);
            } else {
                this.$root.showSnackbar('Please select a notification to delete', 'danger');
            }
        },
    }
};
</script>
