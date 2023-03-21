import { StoreRootType } from "@/stores/store.types";
import { useSelector } from "react-redux";

const useStore = (): StoreRootType => {
  return useSelector<StoreRootType, StoreRootType>((state) => state);
};

export default useStore;
