import { configureStore as configureStoreToolkit } from "@reduxjs/toolkit";
import { persistReducer, persistStore } from "redux-persist";
import storage from "redux-persist/lib/storage";
import rootReducer from "./index";

const persistConfig = {
	key: "root",
	storage,
	whitelist: ["silent"],
	version: 1,
};

const persistedReducer = persistReducer(persistConfig, rootReducer);

const configureStore = () => {
	let store = configureStoreToolkit({
		reducer: persistedReducer,
		middleware: (getDefaultMiddleware) => getDefaultMiddleware({ serializableCheck: false }),
		devTools: process.env.NODE_ENV !== "production",
	});
	let persistor = persistStore(store);
	return { store, persistor };
};

export default configureStore;
