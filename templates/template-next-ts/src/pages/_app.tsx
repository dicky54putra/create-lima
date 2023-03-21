import Loading from "@/components/atoms/Loading";
import configureStore from "@/stores/persist";
import type { AppProps } from "next/app";
import { Suspense } from "react";
import { Provider } from "react-redux";
import { ToastContainer } from "react-toastify";
import { PersistGate } from "redux-persist/lib/integration/react";
import "@/styles/styles.scss";

const { persistor, store } = configureStore();
export default function App({ Component, pageProps }: AppProps) {
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
