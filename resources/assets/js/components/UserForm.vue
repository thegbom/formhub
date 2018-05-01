<template>
    <div class='row'>
        <h1>{{heading}}</h1>
        <form action="#" @submit.prevent="createItem()">
            <div v-for="item in list">
              <div v-if="item.component_type==1">
                <label v-bind:for="item.id">{{ item.label }}</label><br/>
                <input type="text" v-bind:id="item.id" v-bind:name=item.id v-model="item.startvalue" v-bind:placeholder=item.placeholder>
              </div>
              <div v-else-if="item.component_type==2">
                <label v-bind:for="item.id">{{ item.label }}</label><br/>
                <select v-model="item.startvalue" v-bind:id="item.id">
                   <option v-for="option in item.options" v-bind:value="option.value">
                     {{ option.text }}
                   </option>
                </select>
              </div>
            </div>
            <br/><br/>
            <span class="input-group-btn">
               <button type="submit" class="btn btn-primary">Save</button>
            </span>
        </form>
    </div>
</template>
<script>
    export default {
        props: [
         'geturl'
        ],
        data() {
            return {
                list: [],
                header:{},
                heading : '',
                posturl : ''
            };
        },
        
        created() {
            this.fetchItemList();
        },
        
        methods: {
            fetchItemList() {
                axios.get(this.getUrl()).then((res) => {
                    this.header = res.data[0].header;
                    this.list=res.data[1].lines;
                    this.heading=this.header.label;
                    this.posturl='api/form'+'/'+this.header.table;
                    console.log(this.postUrl());
                });
            },
 
            createItem() {
                console.log(this.postUrl());
                axios.post(this.postUrl(), this.list)
                    .then((res) => {
                        this.list=[];
                        this.fetchItemList();
                    })
                    .catch((err) => console.error(err));
            },


            
 
            deleteItem(id) {
                axios.delete(this.getUrl() + '/' + id)
                    .then((res) => {
                        this.fetchItemList()
                    })
                    .catch((err) => console.error(err));
            },

            getUrl(){
               return this.geturl;
            },

            postUrl(){
               return this.posturl;
            }
        }
    }
</script>


