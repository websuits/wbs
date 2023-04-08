import Head from 'next/head'
import {GetStaticProps} from 'next'
import Container from '../components/container'
import MoreStories from '../components/more-stories'
import HeroPost from '../components/hero-post'
import Intro from '../components/intro'
import Header from '../components/Header/Header.component'
import Navigation from '../components/Navigation'
import Layout from '../components/layout'
import {getAllPostsForHome} from '../query/api'
import {getAllMenus} from '../query/allMenus'
import {CMS_NAME} from '../config/constants'

export default function Index({allPosts: {edges}, allMenus, preview}) {
    const heroPost = edges[0]?.node
    const morePosts = edges.slice(1)

    console.log(allMenus);
    return (
        <Layout preview={preview}>
            <Head>
                <title>{`Websuits.ro - ${CMS_NAME}`}</title>
            </Head>
            <Header />
            <Navigation menus={ allMenus.menus } />
            <Container>
                <Intro/>
                {heroPost && (
                    <HeroPost
                        title={heroPost.title}
                        coverImage={heroPost.featuredImage}
                        date={heroPost.date}
                        author={heroPost.author}
                        slug={heroPost.slug}
                        excerpt={heroPost.excerpt}
                    />
                )}
                {morePosts.length > 0 && <MoreStories posts={morePosts}/>}
            </Container>
        </Layout>
    )
}

export const getStaticProps: GetStaticProps = async ({preview = false}) => {
    const allPosts = await getAllPostsForHome(preview)
    const allMenus = await getAllMenus(preview)

    return {
        props: {allPosts, allMenus, preview},
        revalidate: 10,
    }
}
