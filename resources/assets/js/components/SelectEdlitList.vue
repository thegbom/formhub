<template>
    <div class='row'>
        <h1>Select Descriptions and Values for:[{{header}}]</h1>
        <h4></h4>
        <form action="#" @submit.prevent="createItem()">
            <div class="input-group">
                <label for="descr">Description</label><br/>
                <input v-model="item.descr" type="text" name="descr" class="form-control" autofocus><br/>
                <label for="value">value</label><br/>
                <input v-model="item.value" type="text" name="value" class="form-control"><br/>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Save</button>
                </span>
            </div>
        </form>
        <h4>All Items</h4>
        <ul class="list-group">
            <li v-if='list.length === 0'>There are no lines yet!</li>
            <li class="list-group-item" v-for="(item, index) in list">
                 {{ item.description }} {{ item.value }} 
                 <button @click="deleteItem(item.id)" class="btn btn-danger btn-xs pull-right">Delete</button>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: [
         'language',
         'formid'
        ],
        data() {
            return {
                header:'',
                list: [],
                item: {
                    id: '',
                    descr: '',
                    value: ''
                }
            };
        },
        
        created() {
            this.fetchItemList();
            this.fetchItemHeader();
        },
        
        methods: {
            fetchItemList() {
                axios.get('api/formlines/select/'+this.language+'/'+this.formid).then((res) => {
                    this.list = res.data;
                });
            },
            fetchItemHeader() {
                axios.get('api/formlines/labels/'+this.language+'/'+this.formid).then((res) => {
                    //console.log(res)
                    this.header = res.data;
                });
            },
 
            createItem() {
                axios.post('api/formlines/select/'+this.language+'/'+this.formid, this.item)
                    .then((res) => {
                        this.item.descr = '';
                        this.item.value = '';
                        this.edit = false;
                        this.fetchItemList();
                    })
                    .catch((err) => console.error(err));
            },
 
            deleteItem(id) {
                axios.get('api/formlines/select/delete/row/' + id)
                    .then((res) => {
                        this.fetchItemList()
                    })
                    .catch((err) => console.error(err));
            },
        }
    }
</script>
</script>
