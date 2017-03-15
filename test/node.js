/**
 * Created by limx on 2017/2/13.
 */
var obj = {
    test: 'Hello World'
};
console.log(obj.test);
console.log(typeof obj);
console.log(typeof obj.test1);
if (typeof(obj.test1) != "undefined" && typeof(obj.test1.test2) != "undefined") {
    console.log(111);
}
console.log(typeof "aaa");
console.log(typeof 1222);

var a=[];
a['aa']='ff';
console.log(a);