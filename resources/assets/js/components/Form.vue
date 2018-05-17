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
<span>{{item.value}}</span>
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
         'table',
         'language',
         'heading',
         'geturl',
         'posturl',
         'step',
        ],
        data() {
            return {
                list: []
            };
        },
        
        created() {
            console.log('Created... ');
            this.fetchItemList();
        },
        
        methods: {
            fetchItemList() {
                console.log('step: '+this.step+' language: '+this.language+' table: '+this.table);
                axios.get(this.getUrl()).then((res) => {
                       this.list = res.data;
                   });
            },
 
            createItem() {
                axios.post(this.postUrl(), this.list)
                    .then((res) => {
                        //console.log('step: '+this.step+' table: '+this.table+' language: '+this.language);
                        if(this.step==0){
                          let ptable=this.list[0].startvalue;
                          let lang=this.list[1].startvalue;
console.log('step: '+this.step+' table: '+ptable+' language: '+lang);
                          this.$router.push({ name: 'step1', params: { language: lang, table: ptable, heading:'Select the way the columns should be edited', geturl:'api/formlines/componenttype/'+ptable, posturl:'api/formlines', step:1 }});
                          this.$router.go();
                        }else if(this.step==1){
                          console.log("Carried forward: "+this.table);
                          this.$router.push({ name:'step2', params: { language: this.language, table: this.table, heading:'Create the label for each field', geturl:'api/formlines/labels/'+this.table,posturl:'api/formlines/labels/'+this.table, step:2 }})
                          this.$router.go();
                        }else if(this.step==2){
                          console.log('step: '+this.step+' table: '+this.table+' language: '+this.language);
                          this.$router.push({ name:'selectoption',params: { language: this.language, formid:-1, table: this.table, heading:'', geturl:'api/selectedit', posturl:'', step:3}});
                          //this.$router.go();
                        }else{ 
                           this.list=[];
                           this.fetchItemList();
                        }
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

