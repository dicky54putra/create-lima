import { combineReducers } from "@reduxjs/toolkit";
import counterSlice from "@/stores/counter/counter.store";

export default combineReducers({
	counterSlice: counterSlice,
});
