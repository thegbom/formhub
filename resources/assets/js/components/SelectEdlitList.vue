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
        <span class="input-group-btn">
             <button v-on:click="nextField()">Next</button>
        </span>
    </div>
</template>
<script>
    export default {
        props: [
         'language',
         'formid',
         'table'
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
            this.fetchItemList(this.table,-1);
            //this.fetchItemHeader();
            if(this.formid==undefined){
                 this.formid=this.fid;
            }
        },
        
        methods: {
            fetchItemList(table, formid) {
                console.log('Table: '+this.table+" Formid: "+formid);
                axios.get('api/formlines/select/'+this.language+'/'+formid+'/'+table).then((res) => {
                    if(res.data.name !== undefined){
                        this.list = res.data.list;
                        this.header = res.data.name;
                        this.fid = res.data.formid;
                    }else{
                        this.$router.push({ name: 'home'});
                        this.$router.go();
                    }
                });
            },

            
 
            createItem() {
                axios.post('api/formlines/select/'+this.language+'/'+this.fid, this.item)
                    .then((res) => {
                        this.item.descr = '';
                        this.item.value = '';
                        this.edit = false;
                        this.fetchItemList("", this.fid);
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
            
            nextField(){
                this.header='',
                this.list=[],
                this.formid=-1;
                this.fid=-1;
                this.fetchItemList(this.table,-1);            
                if(this.formid==undefined){
                   this.formid=this.fid;
                }
            },
        }
    }
</script>
</script>
