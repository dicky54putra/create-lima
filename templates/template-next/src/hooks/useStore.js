import { useSelector } from "react-redux";

/**
 * @returns {import('../stores/types').StoreRootType}
 */
const useStore = () => {
	return useSelector((state) => state);
};

export default useStore;
