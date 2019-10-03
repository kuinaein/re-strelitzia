import Vue from 'vue';
import './bootstrap';
import Frame from '@/app/Frame';

if ('production' !== process.env.NODE_ENV) {
  window.axios.interceptors.response.use(res => {
    console.log(res);
    return res;
  }, err => {
    console.error(err);
    throw err;
  });
}
window.axios.interceptors.response.use(res => res, err => {
  console.error(err.response);
  throw err;
});

window.axios.interceptors.response.use(res => res, err => {
  if (!err.response || !err.response.data) {
    throw err;
  }
  const data = err.response.data;
  if (422 === err.response.status && data.errors) {
    // バリデーションエラー
    let buf = '';
    for (const field in data.errors) {
      buf += `\n* ${field}`;
      for (const f of data.errors[field]) {
        buf += `\n  * ${f}`;
      }
    }
    throw new Error(buf);
  }
  throw new Error(JSON.stringify(data));
});

window.theApp = new Vue(Object.assign({}, Frame, {
  el: '#app',
//   router,
//   store,
//   i18n,
}));
