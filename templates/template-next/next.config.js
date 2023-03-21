/** @type {import('next').NextConfig} */
const nextConfig = {
	reactStrictMode: true,
	images: {
		// TODO: Don't forget to change domain.
		domains: process.env.NEXT_PUBLIC_IMAGE_DOMAINS?.split(","),
	},
};

module.exports = nextConfig;
