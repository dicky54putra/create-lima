import "@/styles/styles.scss";
import configureStore from "@/stores/persist";
import "react-toastify/dist/ReactToastify.css";
import { PersistGate } from "redux-persist/integration/react";
import { Suspense } from "react";
import { Provider } from "react-redux";
import { ToastContainer } from "react-toastify";
import Loading from "@/components/atoms/Loading";

const { persistor, store } = configureStore();
export default function App({ Component, pageProps }) {
	return (
		<>
			<ToastContainer autoClose={3000} position="bottom-left" limit={3} />

			<Provider store={store}>
				<PersistGate loading={<Loading />} persistor={persistor}>
					<Suspense fallback={<Loading />}>
						<Component {...pageProps} />
					</Suspense>
				</PersistGate>
			</Provider>
		</>
	);
}
